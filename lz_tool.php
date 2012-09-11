#!/usr/bin/php -f 
<?php
/***************************************************************************
 *   Copyright (C) 2007 by Stoian Ivanov                                   *
 *   sdr@mail.bg                                                           *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Library General Public License version  *
 *   2 as published by the Free Software Foundation;                       *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU Library General Public     *
 *   License along with this program; if not, write to the                 *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

	exec ("egrep --include='*.php' -o \"lz\\s*\\(\\s*[\\'\\\"].+[\\'\\\"]\\s*\\)\" *",$res);
	$strs=array();
	foreach ($res as $ln) {
		list($trash,$str) =explode ('(',$ln,2);
		$str=substr(trim($str),1,-2);
		if (array_search($str,$strs)==false){
			$strs[]=$str;
			echo ("$str\n");
		}
	}
	$fs=glob ("localize.*.php");
	global $lz_data;
	foreach ($fs as $f) {
		echo ("file to consider: $f\n");
		unset ($lz_data);
		$toadd=array();
		include ($f);
		if (is_array($lz_data)){
			foreach ($strs as $str) {
				if (!array_key_exists($str,$lz_data)){
					$toadd[]=$str;
					echo " must add $str\n";
				}
			}
			$fh=fopen($f,'rb');
			if (!$fh) {
				echo "Can't open $f for reading\n";
				continue;
			}
			$tmpfname = tempnam("/tmp", "lz_tool_for_".basename($f));
			
			$fht = fopen($tmpfname, "wb+");
			$state=1;
			
			echo "search starts..\n";
			while (($ln=fgets($fh))!==FALSE) {
				if ($state==1) {
					fwrite($fht,$ln);
					if ($ln=="//LZ_TOOL_AUTOADD_BEG\n") {
						echo "//LZ_TOOL_AUTOADD_BEG found!\n";
						foreach ($toadd as $str) fwrite($fht,"'$str'=>'',\n");
						$state=2;
					}
				} else if ($state==2) {
					if ($ln=="//LZ_TOOL_AUTOADD_END\n") {
						echo "//LZ_TOOL_AUTOADD_END found!\n";
						fwrite($fht,$ln);
						$state=3;
					}
				} else if($state==3) {
					fwrite($fht,$ln);
				}
			}
			fclose($fh);
			fclose($fht);
			copy($f,"$f.old".time());
			copy($tmpfname,$f);
			//passthru("cat $tmpfname");
			
			unlink($tmpfname);
		}
	}
?>