<?php 
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
set_time_limit(0);

load()->func('file');
load()->model('cloud');
load()->func('db');

$dos = array('backup', 'restore', 'trim', 'optimize', 'run');
$do = in_array($do, $dos) ? $do : 'backup';

if ($do == 'backup') {
	$_W['page']['title'] = '备份 - 数据库 - 常用系统工具 - 系统管理';
	if ($_GPC['status']) {
		if (empty($_W['setting']['copyright']['status'])) {
			itoast('为了保证备份数据完整请关闭站点后再进行此操作', url('system/site'), 'error');
		}
		
				$sql = "SHOW TABLE STATUS LIKE '{$_W['config']['db']['tablepre']}%'";
		$tables = pdo_fetchall($sql);
		if (empty($tables)) {
			itoast('数据已经备份完成', url('system/database/'), 'success');
		}
		
				 $series = max(1, intval($_GPC['series']));
				if (!empty($_GPC['volume_suffix'])) {
			$volume_suffix =  $_GPC['volume_suffix'];
		} else {
			$volume_suffix = random(10);
		}	
				if (!empty($_GPC['folder_suffix'])) {
			$folder_suffix = $_GPC['folder_suffix'];
		} else {
			$folder_suffix = TIMESTAMP . '_' . random(8);
		}
				$bakdir = IA_ROOT . '/data/backup/' . $folder_suffix;
		if (trim($_GPC['start'])) {
			$result = mkdirs($bakdir);		
		}
				$size = 300;
				$volumn = 1024 * 1024 * 2;
		$dump = '';
				if (empty($_GPC['last_table'])) {
			$last_table ='';
			$catch = true;
		} else {
			$last_table = $_GPC['last_table'];
			$catch = false;
		}
		foreach ($tables as $table) {
						$table = array_shift($table);
						if (!empty($last_table) && $table == $last_table) {
				$catch = true;
			}
						if (!$catch) { 
				continue;
			}
			if (!empty($dump)) {
				$dump .= "\n\n";
			}
						if ($table != $last_table) {
				$row = db_table_schemas($table);
				$dump .= $row;
			}
						$index = 0;
			if (!empty($_GPC['index'])) {
				$index = $_GPC['index'];
				$_GPC['index'] = 0;
			}
						while (true) {
				$start = $index * $size;
				$result = db_table_insert_sql($table, $start, $size);
				if (!empty($result)) {
					$dump .= $result['data'];
					if (strlen($dump) > $volumn) {
						$bakfile = $bakdir . "/volume-{$volume_suffix}-{$series}.sql";
						$dump .= "\n\n";
						file_put_contents($bakfile, $dump);
						$series++;
						$index++;
						$current = array(
							'last_table' => $table,
							'index' => $index,
							'series' => $series,
							'volume_suffix'=>$volume_suffix,
							'folder_suffix'=>$folder_suffix,
							'status'=>1
						);
						$current_series = $series-1;
						itoast('正在导出数据, 请不要关闭浏览器, 当前第 ' . $current_series . ' 卷.', url('system/database/backup/',$current), 'info');
					}
					
				}
				
				if (empty($result) || count($result['result']) < $size) {
					break;
				}
				$index++;
			}
		}
	
				$bakfile = $bakdir . "/volume-{$volume_suffix}-{$series}.sql";
		$dump .= "\n\n----WeEngine MySQL Dump End";
		file_put_contents($bakfile, $dump);
		itoast('数据已经备份完成', url('system/database/'), 'success');	
	}
}

if($do == 'restore') {
	$_W['page']['title'] = '还原 - 数据库 - 常用系统工具 - 系统管理';
	$reduction = array();
	$path = IA_ROOT . '/data/backup/';
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($bakdir = readdir($handle))) {
				if ($bakdir == '.' || $bakdir == '..') {
					continue;
				}
				if (preg_match('/^(?P<time>\d{10})_[a-z\d]{8}$/i', $bakdir, $match)) {
					$time = $match['time'];
										if ($handle1= opendir($path . $bakdir)) {
						while (false !== ($filename = readdir($handle1))) {
							if ($filename == '.' || $filename == '..') {
								continue;
							}
							if (preg_match('/^volume-(?P<prefix>[a-z\d]{10})-\d{1,}\.sql$/i', $filename, $match1)) {
								$volume_prefix = $match1['prefix'];
								if (!empty($volume_prefix)) {
									break;
								}
							}
						}
					}
					for ($i = 1;;) {
						$last = $path . $bakdir . "/volume-{$volume_prefix}-{$i}.sql";
						$i++;
						$next = $path . $bakdir . "/volume-{$volume_prefix}-{$i}.sql";
						if (!is_file($next)) {
							break;
						}
					}
					if (is_file($last)) {
						$fp = fopen($last, 'r');
						fseek($fp, -27, SEEK_END);
						$end = fgets($fp);
						fclose($fp);
						if ($end == '----WeEngine MySQL Dump End') {
							$row = array(
								'bakdir'=> $bakdir,
								'time'=> $time,
								'volume'=> $i - 1
							);
							$reduction[$bakdir] = $row;
							continue;
						}
					}
				}
				rmdirs($path . $bakdir);
			}
		}
	}
	if (!empty($_GPC['restore_dirname'])) {
		$restore_dirname = $_GPC['restore_dirname'];
		if ($reduction[$restore_dirname]) {
			$row = $reduction[$restore_dirname];
			$dir = $path . $row['bakdir'];
						if ($handle1= opendir($dir)) {
				while (false !== ($filename = readdir($handle1))) {
					if ($filename == '.' || $filename == '..') {
						continue;
					}
					if (preg_match('/^volume-(?P<prefix>[a-z\d]{10})-\d{1,}\.sql$/i', $filename, $match1)) {
						$volume_prefix = $match1['prefix'];
						break;
					}
				}
			}
						if (empty($_GPC['restore_volume_prefix'])) {
				$restore_volume_prefix = $volume_prefix;
			} else {
				$restore_volume_prefix = $_GPC['restore_volume_prefix'];
			}
						$restore_volume_sizes = max(1, intval($_GPC['restore_volume_sizes']));
			if ($reduction[$restore_dirname]) {
				if ($reduction[$restore_dirname]['volume'] < $restore_volume_sizes) {
					itoast('成功恢复数据备份. 可能还需要你更新缓存.', url('system/database/restore'), 'success');
				} else {
					$sql = file_get_contents($path .$restore_dirname . "/volume-{$restore_volume_prefix}-{$restore_volume_sizes}.sql");
					pdo_run($sql);
					$volume_sizes = $restore_volume_sizes;
					$restore_volume_sizes ++;
					$restore = array (
						'restore_dirname' => $restore_dirname,
						'restore_volume_prefix' => $restore_volume_prefix,
						'restore_volume_sizes' => $restore_volume_sizes,
					);
					itoast('正在恢复数据备份, 请不要关闭浏览器, 当前第 ' . $volume_sizes . ' 卷.', url('system/database/restore',$restore), 'success');
				}
			} else {
				itoast('非法访问', '','error');
			}
		}
	}

	if ($_GPC['delete_dirname']) {
		$delete_dirname = $_GPC['delete_dirname'];
		if ($reduction[$delete_dirname]) {
			rmdirs($path . $delete_dirname);
			itoast('删除备份成功.', url('system/database/restore'), 'success');
		}
	}
}

if ($do == 'trim') {
	if ($_W['ispost']) {
		$type = $_GPC['type'];
		$data = $_GPC['data'];
		$table = $_GPC['table'];
		if ($type == 'field') {
			$sql = "ALTER TABLE `$table` DROP `$data`";
			if (false !== pdo_query($sql, $params)) {
				exit('success');
			}
		} elseif ($type == 'index') {
			$sql = "ALTER TABLE `$table` DROP INDEX `$data`";
			if (false !== pdo_query($sql, $params)) {
				exit('success');
			}
		}
		exit();
	}

	
	$upgrade = cloud_schema();
	$schemas = $upgrade['schemas'];
	
	
	if (!empty($schemas)) {
		foreach ($schemas as $key=>$value) {
			$tablename =  substr($value['tablename'], 4);
			$struct = db_table_schema(pdo(), $tablename);
			if (!empty($struct)) {
				$temp = db_schema_compare($schemas[$key],$struct);
				if (!empty($temp['fields']['less'])) {
					$diff[$tablename]['name'] = $value['tablename'];
					foreach ($temp['fields']['less'] as $key=>$value) {
						$diff[$tablename]['fields'][] = $value;
					}
				}
				if (!empty($temp['indexes']['less'])) {
					$diff[$tablename]['name'] = $value['tablename'];
					foreach ($temp['indexes']['less'] as $key=>$value) {
						$diff[$tablename]['indexes'][] = $value;
					}
				}
			}
		}
	}
}
if ($do == 'optimize') {
	$_W['page']['title'] = '优化 - 数据库 - 常用系统工具 - 系统管理';
	$optimize_table = array();
	$sql = "SHOW TABLE STATUS LIKE '{$_W['config']['db']['tablepre']}%'";
	$tables = pdo_fetchall($sql);
	foreach ($tables as $tableinfo) {
		if ($tableinfo['Engine'] == 'InnoDB') {
			continue;
		}
		if (!empty($tableinfo) && !empty($tableinfo['Data_free'])) {
			$row = array(
				'title' => $tableinfo['Name'],
				'type' => $tableinfo['Engine'],
				'rows' => $tableinfo['Rows'],
				'data' => sizecount($tableinfo['Data_length']),
				'index' => sizecount($tableinfo['Index_length']),
				'free' => sizecount($tableinfo['Data_free'])
			);
			$optimize_table[$row['title']] = $row;
		}
	}

	if (checksubmit()) {
		foreach ($_GPC['select'] as $tablename) {
			if (!empty($optimize_table[$tablename])) {
				$sql = "OPTIMIZE TABLE {$tablename}";
				pdo_fetch($sql);
			}
		}
		itoast('数据表优化成功.', 'refresh', 'success');
	}
}
if ($do == 'run') {
	$_W['page']['title'] = '运行SQL - 数据库 - 常用系统工具 - 系统管理';
	if (!DEVELOPMENT) {
		itoast('请先开启开发模式后再使用此功能', referer(), 'info');
	}
	if (checksubmit()) {
		$sql = $_POST['sql'];
		pdo_run($sql);
		itoast('查询执行成功.', 'refresh', 'success');
	}
}

template('system/database');

