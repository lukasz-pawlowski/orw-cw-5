
<?php
/* implementacja cron */

$cronfile='crontab.txt';

//zapis
if(isset($_POST['cron'])){
	$newjob = $_POST['cron'];

	if(!empty($_POST['komenda'])){
		$cronline = "\r\n".
			$newjob['minuta']." ".
			$newjob['godzina']." ".
			$newjob['dzien_m']." ".
			$newjob['miesiac']." ".
			$newjob['dzien_t']." ".
			$_POST['komenda'];
		
		$result = file_put_contents($cronfile, $cronline, FILE_APPEND);
		
		if($result !== false)
			echo "Zapisano poprawnie!";
		else
			echo "Blad zapisu!";
	}
	else echo "Komenda nie moze byc pusta!";
}

//usuwanie
if(isset($_POST['delete'])){

	//czytanie
	if(file_exists($cronfile)){
		$cronjobs = file_get_contents($cronfile);
		$cronjobs = explode("\r\n", $cronjobs);
	}
	
	//usuwanie
	$newtable = "";
	foreach($cronjobs as $job){
		if(md5($job) != $_POST['delete'])
			$newtable .= $job."\r\n";
	}
	
	//zapis
	$result = file_put_contents($cronfile, $newtable);
	
	if($result !== false)
		echo "Usunieto poprawnie!";
	else
		echo "Blad zapisu!";

}

//czytanie
if(file_exists($cronfile)){
	$cronjobs = file_get_contents($cronfile);
	$cronjobs = explode("\r\n", $cronjobs);
}



?>
<html>
<head>
<meta charset="utf-8">
<title>Cron</title>
</head>
<body>

<h2>Lista Cron</h2>

<table>
	<tr>
		<th>Minuta</th>
		<th>Godzina</th>
		<th>Dzien miesiaca</th>
		<th>Miesiac</th>
		<th>Dzien tygodnia</th>
		<th>Komenda</th>
	</tr>
	
<? if(!empty($cronjobs)) { 

foreach($cronjobs as $cronjob){
$cron = explode(' ', $cronjob);
	if(!empty($cron[0])){
		
		//laczenie komendy ze spacjami
		$komenda = "";
		for($i = 5; $i < count($cron); $i++){
			$komenda .= $cron[$i]." ";
		}
	?>
	<tr>
		<td><?=$cron[0]?></td>
		<td><?=$cron[1]?></td>
		<td><?=$cron[2]?></td>
		<td><?=$cron[3]?></td>
		<td><?=$cron[4]?></td>
		<td><?=$komenda?></td>
		<td>
			<form method="post">
				<input type="hidden" name="action" value="delete" />
				<input type="hidden" name="delete" value="<?=md5($cronjob)?>" >
				<input type="submit" value="Usun">
			</form>
		</td>
	</tr>
<? } } } ?>
</table>

<h2>Dodaj zadanie Cron</h2>

<form method="post">

	<p>Minuta: 
		<select name="cron[minuta]">
			<option value="*">*</option>
			<? for($i=0; $i< 60; $i++){ ?>
				<option value="<?=$i?>"><?=$i?></option>
			<? } ?>
		</select>
	</p>
	<p>Godzina: 
		<select name="cron[godzina]">
			<option value="*">*</option>
			<? for($i=0; $i< 24; $i++){ ?>
				<option value="<?=$i?>"><?=$i?></option>
			<? } ?>
		</select>
	</p>
	<p>Dzien miesiaca: 
		<select name="cron[dzien_m]">
			<option value="*">*</option>
			<? for($i=1; $i< 32; $i++){ ?>
				<option value="<?=$i?>"><?=$i?></option>
			<? } ?>
		</select>
	</p>
	<p>Miesiac: 
		<select name="cron[miesiac]">
			<option value="*">*</option>
			<? for($i=1; $i< 13; $i++){ ?>
				<option value="<?=$i?>"><?=$i?></option>
			<? } ?>
		</select>
	</p>
	<p>Dzien tygodnia: 
		<select name="cron[dzien_t]">
			<option value="*">*</option>
			<? for($i=0; $i< 8; $i++){ ?>
				<option value="<?=$i?>"><?=$i?></option>
			<? } ?>
		</select>
	</p>
	<p>Komenda: 
		<input type="text" name="komenda" />
	</p>
	
	<p>
		<input type="submit" value="Wyslij" />
	</p>
	<input type="hidden" name="action" value="insert"> 
</form>
	

</body>
</html>