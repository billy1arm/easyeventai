<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<center><font color="Blue" size="5"><b>Easy EventAI!</b></font></center></br>
<center><a href="EventAI.txt" target="_blank">EventAI.doc - оригинальный мануал</a></center>
<script src="http://ru.wowhead.com/widgets/power.js"></script>
<?php
/* Script by Kirix for YTDB
Скрипт by KiriX, специально для YTDB */
$host			= 'localhost'; // host
$user			= 'root';
$password		= '';
$database		= ''; // your database
/*$table			= 'creature_template'; // creature table name
$table_locale	= 'locales_creature'; // locale (only for russian version)
$locale			= '8'; // locale. Defaul: 8 (Russian locale)
$version		= '0.1.3'; // script version
$lang=8; // script lang: 0 - English, 8 - Russian.
$type=0; // type of search: 0 - entry, 1 - name. Default: 1.
$form_lang='1';*/

include ("lang.php");
include_once ("DbSimple/Generic.php");
$dDB = DbSimple_Generic::connect("mysql://root:13589436@127.0.0.1/ytdb-w");
mysql_query('SET NAMES utf8;');

$id		= @$_REQUEST['id'];
$creature_id		= @$_REQUEST['creature_id'];
$event_inverse_phase_mask		= @$_REQUEST['event_inverse_phase_mask'];
if ($event_inverse_phase_mask == '') $event_inverse_phase_mask = '0';
$event_chance		= @$_REQUEST['event_chance'];
if ($event_chance == '') $event_chance = '100';
$event_flags		= @$_REQUEST['event_flags'];
if ($event_flags == '') $event_flags = '0';
$event_param1		= @$_REQUEST['event_param1'];
if ($event_param1 == '') $event_param1 = '0';
$event_param2		= @$_REQUEST['event_param2'];
if ($event_param2 == '') $event_param2 = '0';
$event_param3		= @$_REQUEST['event_param3'];
if ($event_param3 == '') $event_param3 = '0';
$event_param4		= @$_REQUEST['event_param4'];
if ($event_param4 == '') $event_param4 = '0';
$action1_param1		= @$_REQUEST['action1_param1'];
if ($action1_param1 == '') $action1_param1 = '0';
$action1_param2		= @$_REQUEST['action1_param2'];
if ($action1_param2 == '') $action1_param2 = '0';
$action1_param3		= @$_REQUEST['action1_param3'];
if ($action1_param3 == '') $action1_param3 = '0';
$action2_param1		= @$_REQUEST['action2_param1'];
if ($action2_param1 == '') $action2_param1 = '0';
$action2_param2		= @$_REQUEST['action2_param2'];
if ($action2_param2 == '') $action2_param2 = '0';
$action2_param3		= @$_REQUEST['action2_param3'];
if ($action2_param3 == '') $action2_param3 = '0';
$action3_param1		= @$_REQUEST['action3_param1'];
if ($action3_param1 == '') $action3_param1 = '0';
$action3_param2		= @$_REQUEST['action3_param2'];
if ($action3_param2 == '') $action3_param2 = '0';
$action3_param3		= @$_REQUEST['action3_param3'];
if ($action3_param3 == '') $action3_param3 = '0';

if ($search==1)
{
$script = $dDB-> selectRow("SELECT * FROM `creature_ai_scripts` WHERE `id` = ".$id."");
$creature_id 				= $script[creature_id];
$event_param1 				= $script[event_param1];
$event_inverse_phase_mask 	= $script[event_inverse_phase_mask];
$event_chance				= $script[event_chance];
$event_flags				= $script[event_flags];
$event_type					= $script[event_type];
$event_param1				= $script[event_param1];
$event_param2				= $script[event_param2];
$event_param3				= $script[event_param3];
$event_param4				= $script[event_param4];
$action1_type				= $script[action1_type];
$action1_param1				= $script[action1_param1];
$action1_param2				= $script[action1_param2];
$action1_param3				= $script[action1_param3];
$action2_type				= $script[action2_type];
$action2_param1				= $script[action2_param1];
$action2_param2				= $script[action2_param2];
$action2_param3				= $script[action2_param3];
$action3_type				= $script[action3_type];
$action3_param1				= $script[action3_param1];
$action3_param2				= $script[action3_param2];
$action3_param3				= $script[action3_param3];
$comment					= $script[comment];
}
$name = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$creature_id."");
?>
<html>
	<head>
		<title><?php echo $lang_title; ?></title>
	</head>
<body>
	<form name="npc" method="post">
	<?php echo $lang_form; ?>:
		<table border="1">
			<tr><td><?php echo "Номер скрипта"; ?>:</td><td><input name="id" type="text" value="<?php echo $id; ?>"> <button value="1" name="search" type="submit">Поиск в базе</button></td>
			<tr><td><?php echo "Entry НПС"; ?>:</td><td><input name="creature_id" type="text" value="<?php echo $creature_id; ?>">  <?php echo "<a href=\"http://ru.wowhead.com/?npc=$creature_id#abilities\" target=\"_blank\">$name</a>";?></td>
			<tr><td><?php echo "Фаза события"; ?>:</td><td><input name="event_inverse_phase_mask" type="text" value="<?php echo $event_inverse_phase_mask; ?>"></td>
			<tr><td><?php echo "Шанс срабатывания"; ?>:</td><td><input name="event_chance" type="text" value="<?php echo $event_chance; ?>"> %</td>
			<tr><td><?php echo "Повторяемость"; ?>:</td>
				<td><input type="checkbox" name="event_flags1" value="1" <?php if ($event_flags&1) {?>checked<?php }?>>Повторяемый
				<input type="checkbox" name="event_flags4" value="128" <?php if ($event_flags&128) {?>checked<?php }?>>Debug<Br>
				<input type="checkbox" name="event_flags2" value="2" <?php if ($event_flags&2) {?>checked<?php }?>>В Normal-mod
				<input type="checkbox" name="event_flags3" value="4" <?php if ($event_flags&4) {?>checked<?php }?>>В Heroic-mod</td>
			<?php $event_flags = $event_flags1+$event_flags2+$event_flags3+$event_flags4; ?>
			<tr><td><?php echo "Тип события"; ?>:</td><td>
			<form method="post">
			<select name="event_type">
				<option value="0" <?php if ($event_type == 0) {?> selected <?php }?>>По таймеру в бою</option>
				<option value="1" <?php if ($event_type == 1) {?> selected <?php }?>>По таймеру вне боя</option>
				<option value="2" <?php if ($event_type == 2) {?> selected <?php }?>>При значении HP</option>
				<option value="3" <?php if ($event_type == 3) {?> selected <?php }?>>При значении MP</option>
				<option value="4" <?php if ($event_type == 4) {?> selected <?php }?>>При зааггривании</option>
				<option value="5" <?php if ($event_type == 5) {?> selected <?php }?>>При убийстве цели</option>
				<option value="6" <?php if ($event_type == 6) {?> selected <?php }?>>При смерти</option>
				<option value="7" <?php if ($event_type == 7) {?> selected <?php }?>>При уходе в эвейд</option>
				<option value="8" <?php if ($event_type == 8) {?> selected <?php }?>>По урону спеллом</option>
				<option value="9" <?php if ($event_type == 9) {?> selected <?php }?>>При дистанции</option>
				<option value="10" <?php if ($event_type == 10) {?> selected <?php }?>>При появлении в зоне LOS</option>
				<option value="11" <?php if ($event_type == 11) {?> selected <?php }?>>При спавне</option>
				<option value="12" <?php if ($event_type == 12) {?> selected <?php }?>>При значении НР цели</option>
				<option value="13" <?php if ($event_type == 13) {?> selected <?php }?>>Если цель кастует</option>
				<option value="14" <?php if ($event_type == 14) {?> selected <?php }?>>При значении HP дружественной цели</option>
				<option value="15" <?php if ($event_type == 15) {?> selected <?php }?>>Если дружественная цель под контролем</option>
				<option value="16" <?php if ($event_type == 16) {?> selected <?php }?>>Если теряет бафф</option>
				<option value="17" <?php if ($event_type == 17) {?> selected <?php }?>>При спавне НПС</option>
				<option value="21" <?php if ($event_type == 21) {?> selected <?php }?>>По возвращению в точку спавна</option>
				<option value="22" <?php if ($event_type == 22) {?> selected <?php }?>>При получении эмоции</option>
			</select> <input type="submit"value="Дальше..."></td>
			<?php if ($event_type == 0) {?>
			<tr><td><?php echo "Минимальное время до срабатывания"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до срабатывания"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 1) {?>
			<tr><td><?php echo "Минимальное время до срабатывания"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до срабатывания"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 2) {?>
			<tr><td><?php echo "Максимальное значение HP"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> %</td>
			<tr><td><?php echo "Минимальное значение HP"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> %</td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 3) {?>
			<tr><td><?php echo "Максимальное значение MP"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> %</td>
			<tr><td><?php echo "Минимальное значение MP"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> %</td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 4) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$event_param1 = 0;
					$event_param2 = 0;
					$event_param3 = 0;
					$event_param4 = 0;}?>
			<?php if ($event_type == 5) {?>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 6) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$event_param1 = 0;
					$event_param2 = 0;
					$event_param3 = 0;
					$event_param4 = 0;}?>
			<?php if ($event_type == 7) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$event_param1 = 0;
					$event_param2 = 0;
					$event_param3 = 0;
					$event_param4 = 0;}?>
			<?php if ($event_type == 8) {?>
			<tr><td><?php echo "SpellID (если по школе, оставьте 0)"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> <?php $spell=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$event_param1.""); echo "<a href=\"http://ru.wowhead.com/?spell=$event_param1\" target=\"_blank\">$event_param1 $spell[spellname_loc8] $spell[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Spell School (если по SpellID, установите -1)"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 9) {?>
			<tr><td><?php echo "Минимальная дистанция до цели"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Максимальная дистанция до цели"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 10) {?>
			<tr><td><?php echo "Враг(0) или друг(1)"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Максимальная дистанция до цели"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 11) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$event_param1 = 0;
					$event_param2 = 0;
					$event_param3 = 0;
					$event_param4 = 0;}?>
			<?php if ($event_type == 12) {?>
			<tr><td><?php echo "Максимальное значение HP"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> %</td>
			<tr><td><?php echo "Минимальное значение HP"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> %</td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 13) {?>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 14) {?>
			<tr><td><?php echo "Дефицит HP друж. цели"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> %</td>
			<tr><td><?php echo "Радиус действия"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 15) {?>
			<tr><td><?php echo "Тип диспелла"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Радиус"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 16) {?>
			<tr><td><?php echo "SpellID, дающий ауру"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> <?php $spell=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$event_param1.""); echo "<a href=\"http://ru.wowhead.com/?spell=$event_param1\" target=\"_blank\">$event_param1 $spell[spellname_loc8] $spell[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Радиус"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 17) {?>
			<tr><td><?php echo "creature_id"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Минимальное время до повтора"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<tr><td><?php echo "Максимальное время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 21) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$event_param1 = 0;
					$event_param2 = 0;
					$event_param3 = 0;
					$event_param4 = 0;}?>
			<?php if ($event_type == 22) {?>
			<tr><td><?php echo "ID эмоции"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Условие"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Парметр_1 условия"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"></td>
			<tr><td><?php echo "Парметр_2 условия"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"></td>
			<?php }?>
			<tr><td><?php echo "Тип действия 1"; ?>:</td><td>
			<select name="action1_type">
				<option value="0" <?php if ($action1_type == 0) {?> selected <?php }?>>Ничего</option>
				<option value="1" <?php if ($action1_type == 1) {?> selected <?php }?>>Случайный текст</option>
				<option value="2" <?php if ($action1_type == 2) {?> selected <?php }?>>Сменить фракцию НПС</option>
				<option value="3" <?php if ($action1_type == 3) {?> selected <?php }?>>Сменить модель НПС</option>
				<option value="4" <?php if ($action1_type == 4) {?> selected <?php }?>>Проиграть SOUND</option>
				<option value="5" <?php if ($action1_type == 5) {?> selected <?php }?>>Проиграть эмоцию</option>
				<option value="9" <?php if ($action1_type == 9) {?> selected <?php }?>>Проиграть случайно SOUND</option>
				<option value="10" <?php if ($action1_type == 10) {?> selected <?php }?>>Проиграть случайно эмоцию</option>
				<option value="11" <?php if ($action1_type == 11) {?> selected <?php }?>>Каст спелла</option>
				<option value="12" <?php if ($action1_type == 12) {?> selected <?php }?>>Призыв НПС</option>
				<option value="13" <?php if ($action1_type == 13) {?> selected <?php }?>>Изменить угрозу для опред. цели</option>
				<option value="14" <?php if ($action1_type == 14) {?> selected <?php }?>>Изменить угрозу для всех целей</option>
				<option value="15" <?php if ($action1_type == 15) {?> selected <?php }?>>Explored для квеста</option>
				<option value="16" <?php if ($action1_type == 16) {?> selected <?php }?>>Засчитать каст спелла на НПС/ГО для цели</option>
				<option value="17" <?php if ($action1_type == 17) {?> selected <?php }?>>Изменить UNIT_FIELD</option>
				<option value="18" <?php if ($action1_type == 18) {?> selected <?php }?>>Изменить UNIT_FLAG</option>
				<option value="19" <?php if ($action1_type == 19) {?> selected <?php }?>>Убрать UNIT_FLAG</option>
				<option value="20" <?php if ($action1_type == 20) {?> selected <?php }?>>Авто melee атака</option>
				<option value="21" <?php if ($action1_type == 21) {?> selected <?php }?>>Движение НПС</option>
				<option value="22" <?php if ($action1_type == 22) {?> selected <?php }?>>Установить фазу</option>
				<option value="23" <?php if ($action1_type == 23) {?> selected <?php }?>>Повысить фазу</option>
				<option value="24" <?php if ($action1_type == 24) {?> selected <?php }?>>Уйти в эвейд</option>
				<option value="25" <?php if ($action1_type == 25) {?> selected <?php }?>>Побег с поля боя</option>
				<option value="26" <?php if ($action1_type == 26) {?> selected <?php }?>>Завершить квест для группы</option>
				<option value="27" <?php if ($action1_type == 27) {?> selected <?php }?>>Засчитать каст спелла на НПС/ГО для группы</option>
				<option value="28" <?php if ($action1_type == 28) {?> selected <?php }?>>Убрать с цели ауру от спелла</option>
				<option value="29" <?php if ($action1_type == 29) {?> selected <?php }?>>29 ACTION_T_RANGED_MOVEMENT</option>
				<option value="30" <?php if ($action1_type == 30) {?> selected <?php }?>>Установить фазу рандомно</option>
				<option value="31" <?php if ($action1_type == 31) {?> selected <?php }?>>Установить фазу в заданном параметре</option>
				<option value="32" <?php if ($action1_type == 32) {?> selected <?php }?>>Призыв НПС в опред. точку</option>
				<option value="33" <?php if ($action1_type == 33) {?> selected <?php }?>>Зачитать убийство опред. НПС для цели</option>
				<option value="34" <?php if ($action1_type == 34) {?> selected <?php }?>>34 ACTION_T_SET_INST_DATA</option>
				<option value="35" <?php if ($action1_type == 35) {?> selected <?php }?>>35 ACTION_T_SET_INST_DATA64</option>
				<option value="36" <?php if ($action1_type == 36) {?> selected <?php }?>>Изменить creature_template для НПС</option>
				<option value="37" <?php if ($action1_type == 37) {?> selected <?php }?>>Смерть НПС</option>
				<option value="38" <?php if ($action1_type == 38) {?> selected <?php }?>>Ввести всех игроков инста в бой</option>
			</select> <input type="submit"value="Дальше..."></td>
			<?php if ($action1_type == 1) {?>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action1_param3" type="text" value="<?php echo $action1_param3; ?>"></td>
			<?php }?>
			<?php if ($action1_type == 2) {?>
			<tr><td><?php echo "FactionId из Faction.dbc. 0 для возврата оригинальной фракции"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 3) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action1_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "modelID, если первый параметр = 0"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 4) {?>
			<tr><td><?php echo "SoundID из DBC"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 5) {?>
			<tr><td><?php echo "EmoteID из DBC"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 9) {?>
			<tr><td><?php echo "SoundID_1 из DBC"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "SoundID_2 из DBC"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<tr><td><?php echo "SoundID_3 из DBC"; ?>:</td><td><input name="action1_param3" type="text" value="<?php echo $action1_param3; ?>"></td>
			<?php }?>
			<?php if ($action1_type == 10) {?>
			<tr><td><?php echo "EmoteID_1 из DBC"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "EmoteID_2 из DBC"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<tr><td><?php echo "EmoteID_3 из DBC"; ?>:</td><td><input name="action1_param3" type="text" value="<?php echo $action1_param3; ?>"></td>
			<?php }?>
			<?php if ($action1_type == 11) {?>
			<tr><td><?php echo "SpellID"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $spell1=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action1_param1\" target=\"_blank\">$action1_param1 $spell1[spellname_loc8] $spell1[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "Флаг каста"; ?>:</td>
				<td><input type="checkbox" name="cast_flags1" value="1" <?php if ($action1_param3&1) {?>checked<?php }?>>Прерывает любой другой каст<br>
				<input type="checkbox" name="cast_flags2" value="2" <?php if ($action1_param3&2) {?>checked<?php }?>>Инстант каст без требования маны/реагентов<br>
				<input type="checkbox" name="cast_flags3" value="4" <?php if ($action1_param3&4) {?>checked<?php }?>>Каст если цель далеко или нет маны<br>
				<input type="checkbox" name="cast_flags4" value="8" <?php if ($action1_param3&8) {?>checked<?php }?>>Запрет начала мили атаки<br>
				<input type="checkbox" name="cast_flags5" value="16" <?php if ($action1_param3&16) {?>checked<?php }?>>Каст этого спелла целью<br>
				<input type="checkbox" name="cast_flags6" value="32" <?php if ($action1_param3&32) {?>checked<?php }?>>Только если на цели нет ауры от этого спелла</td>
			<?php $action1_param3 = $cast_flags1+$cast_flags2+$cast_flags3+$cast_flags4+$cast_flags5+$cast_flags6; ?>
			<?php }?>
			<?php if ($action1_type == 12) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action1_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель суммона"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "Длительность призыва"; ?>:</td><td><input name="action1_param3" type="text" value="<?php echo $action1_param3; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($action1_type == 13) {?>
			<tr><td><?php echo "Процент увеличения (от -100 до +100)"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> %</td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 14) {?>
			<tr><td><?php echo "Процент увеличения (от -100 до +100)"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> %</td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 15) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 16) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action1_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "SpellID для каста на НПС из первого параметра"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"> <?php $spell1=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action1_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action1_param2\" target=\"_blank\">$action1_param2 $spell1[spellname_loc8] $spell1[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param3">
					<option value="0" <?php if ($action1_param3 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param3 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param3 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param3 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param3 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param3 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param3 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php }?>
			<?php if ($action1_type == 17) {?>
			<tr><td><?php echo "Номер поля DATA"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Значение"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param3">
					<option value="0" <?php if ($action1_param3 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param3 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param3 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param3 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param3 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param3 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param3 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php }?>
			<?php if ($action1_type == 18) {?>
			<tr><td><?php echo "Флаг"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 19) {?>
			<tr><td><?php echo "Флаг"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 20) {?>
			<tr><td><?php echo "Если 0 - НПС прекратит мили атаку, в противном случае - продолжит/начнёт"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 21) {?>
			<tr><td><?php echo "Если 0 - НПС прекратит движение, в противном случае - продолжит/начнёт"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 22) {?>
			<tr><td><?php echo "Номер новой фазы"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 23) {?>
			<tr><td><?php echo "Число, на которое увеличится номер фазы"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 24) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action1_param1 = 0;
					$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 25) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action1_param1 = 0;
					$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 26) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 27) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "SpellID для симуляции каста"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"> <?php $spell1=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action1_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action1_param2\" target=\"_blank\">$action1_param2 $spell1[spellname_loc8] $spell1[rank_loc0]</a>";?></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 28) {?>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param1">
					<option value="0" <?php if ($action1_param1 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param1 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param1 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param1 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param1 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param1 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param1 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "SpellID, вызвавший ауру"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"> <?php $spell1=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action1_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action1_param2\" target=\"_blank\">$action1_param2 $spell1[spellname_loc8] $spell1[rank_loc0]</a>";?></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 29) {?>
			<tr><td><?php echo "Distance (???)"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Angle (???)"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 30) {?>
			<tr><td><?php echo "Фаза_1"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Фаза_2"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<tr><td><?php echo "Фаза_3"; ?>:</td><td><input name="action1_param3" type="text" value="<?php echo $action1_param3; ?>"></td>
			<?php }?>
			<?php if ($action1_type == 31) {?>
			<tr><td><?php echo "Минимальный номер фазы"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Максимальный номер фазы"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 32) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action1_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель суммона"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "id из creature_ai_summons"; ?>:</td><td><input name="action1_param3" type="text" value="<?php echo $action1_param3; ?>"></td>
			<?php }?>
			<?php if ($action1_type == 33) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action1_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 34) {?>
			<tr><td><?php echo "Поле"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Значение"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 35) {?>
			<tr><td><?php echo "Поле"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action1_param2">
					<option value="0" <?php if ($action1_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action1_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action1_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action1_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action1_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action1_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action1_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 36) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action1_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "model_id для Альянса (0) или Орды (1)"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
			<?php 	$action1_param3 = 0;}?>
			<?php if ($action1_type == 37) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action1_param1 = 0;
					$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 38) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action1_param1 = 0;
					$action1_param2 = 0;
					$action1_param3 = 0;}?>
					
<!-- Action 2 -->
			<tr><td><?php echo "Тип действия 2"; ?>:</td><td>
			<select name="action2_type">
				<option value="0" <?php if ($action2_type == 0) {?> selected <?php }?>>Ничего</option>
				<option value="1" <?php if ($action2_type == 1) {?> selected <?php }?>>Случайный текст</option>
				<option value="2" <?php if ($action2_type == 2) {?> selected <?php }?>>Сменить фракцию НПС</option>
				<option value="3" <?php if ($action2_type == 3) {?> selected <?php }?>>Сменить модель НПС</option>
				<option value="4" <?php if ($action2_type == 4) {?> selected <?php }?>>Проиграть SOUND</option>
				<option value="5" <?php if ($action2_type == 5) {?> selected <?php }?>>Проиграть эмоцию</option>
				<option value="9" <?php if ($action2_type == 9) {?> selected <?php }?>>Проиграть случайно SOUND</option>
				<option value="10" <?php if ($action2_type == 10) {?> selected <?php }?>>Проиграть случайно эмоцию</option>
				<option value="11" <?php if ($action2_type == 11) {?> selected <?php }?>>Каст спелла</option>
				<option value="12" <?php if ($action2_type == 12) {?> selected <?php }?>>Призыв НПС</option>
				<option value="13" <?php if ($action2_type == 13) {?> selected <?php }?>>Изменить угрозу для опред. цели</option>
				<option value="14" <?php if ($action2_type == 14) {?> selected <?php }?>>Изменить угрозу для всех целей</option>
				<option value="15" <?php if ($action2_type == 15) {?> selected <?php }?>>Explored для квеста</option>
				<option value="16" <?php if ($action2_type == 16) {?> selected <?php }?>>Засчитать каст спелла на НПС/ГО для цели</option>
				<option value="17" <?php if ($action2_type == 17) {?> selected <?php }?>>Изменить UNIT_FIELD</option>
				<option value="18" <?php if ($action2_type == 18) {?> selected <?php }?>>Изменить UNIT_FLAG</option>
				<option value="19" <?php if ($action2_type == 19) {?> selected <?php }?>>Убрать UNIT_FLAG</option>
				<option value="20" <?php if ($action2_type == 20) {?> selected <?php }?>>Авто melee атака</option>
				<option value="21" <?php if ($action2_type == 21) {?> selected <?php }?>>Движение НПС</option>
				<option value="22" <?php if ($action2_type == 22) {?> selected <?php }?>>Установить фазу</option>
				<option value="23" <?php if ($action2_type == 23) {?> selected <?php }?>>Повысить фазу</option>
				<option value="24" <?php if ($action2_type == 24) {?> selected <?php }?>>Уйти в эвейд</option>
				<option value="25" <?php if ($action2_type == 25) {?> selected <?php }?>>Побег с поля боя</option>
				<option value="26" <?php if ($action2_type == 26) {?> selected <?php }?>>Завершить квест для группы</option>
				<option value="27" <?php if ($action2_type == 27) {?> selected <?php }?>>Засчитать каст спелла на НПС/ГО для группы</option>
				<option value="28" <?php if ($action2_type == 28) {?> selected <?php }?>>Убрать с цели ауру от спелла</option>
				<option value="29" <?php if ($action2_type == 29) {?> selected <?php }?>>29 ACTION_T_RANGED_MOVEMENT</option>
				<option value="30" <?php if ($action2_type == 30) {?> selected <?php }?>>Установить фазу рандомно</option>
				<option value="31" <?php if ($action2_type == 31) {?> selected <?php }?>>Установить фазу в заданном параметре</option>
				<option value="32" <?php if ($action2_type == 32) {?> selected <?php }?>>Призыв НПС в опред. точку</option>
				<option value="33" <?php if ($action2_type == 33) {?> selected <?php }?>>Зачитать убийство опред. НПС для цели</option>
				<option value="34" <?php if ($action2_type == 34) {?> selected <?php }?>>34 ACTION_T_SET_INST_DATA</option>
				<option value="35" <?php if ($action2_type == 35) {?> selected <?php }?>>35 ACTION_T_SET_INST_DATA64</option>
				<option value="36" <?php if ($action2_type == 36) {?> selected <?php }?>>Изменить creature_template для НПС</option>
				<option value="37" <?php if ($action2_type == 37) {?> selected <?php }?>>Смерть НПС</option>
				<option value="38" <?php if ($action2_type == 38) {?> selected <?php }?>>Ввести всех игроков инста в бой</option>
			</select> <input type="submit"value="Дальше..."></td>
			<?php if ($action2_type == 1) {?>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action2_param3" type="text" value="<?php echo $action2_param3; ?>"></td>
			<?php }?>
			<?php if ($action2_type == 2) {?>
			<tr><td><?php echo "FactionId из Faction.dbc. 0 для возврата оригинальной фракции"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 3) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action2_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "modelID, если первый параметр = 0"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 4) {?>
			<tr><td><?php echo "SoundID из DBC"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 5) {?>
			<tr><td><?php echo "EmoteID из DBC"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 9) {?>
			<tr><td><?php echo "SoundID_1 из DBC"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "SoundID_2 из DBC"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<tr><td><?php echo "SoundID_3 из DBC"; ?>:</td><td><input name="action2_param3" type="text" value="<?php echo $action2_param3; ?>"></td>
			<?php }?>
			<?php if ($action2_type == 10) {?>
			<tr><td><?php echo "EmoteID_1 из DBC"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "EmoteID_2 из DBC"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<tr><td><?php echo "EmoteID_3 из DBC"; ?>:</td><td><input name="action2_param3" type="text" value="<?php echo $action2_param3; ?>"></td>
			<?php }?>
			<?php if ($action2_type == 11) {?>
			<tr><td><?php echo "SpellID"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $spell2=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action2_param1\" target=\"_blank\">$action2_param1 $spell2[spellname_loc8] $spell2[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "Флаг каста"; ?>:</td>
				<td><input type="checkbox" name="cast2_flags1" value="1" <?php if ($action2_param3&1) {?>checked<?php }?>>Прерывает любой другой каст<br>
				<input type="checkbox" name="cast2_flags2" value="2" <?php if ($action2_param3&2) {?>checked<?php }?>>Инстант каст без требования маны/реагентов<br>
				<input type="checkbox" name="cast2_flags3" value="4" <?php if ($action2_param3&4) {?>checked<?php }?>>Каст если цель далеко или нет маны<br>
				<input type="checkbox" name="cast2_flags4" value="8" <?php if ($action2_param3&8) {?>checked<?php }?>>Запрет начала мили атаки<br>
				<input type="checkbox" name="cast2_flags5" value="16" <?php if ($action2_param3&16) {?>checked<?php }?>>Каст этого спелла целью<br>
				<input type="checkbox" name="cast2_flags6" value="32" <?php if ($action2_param3&32) {?>checked<?php }?>>Только если на цели нет ауры от этого спелла</td>
			<?php $action2_param3 = $cast2_flags1+$cast2_flags2+$cast2_flags3+$cast2_flags4+$cast2_flags5+$cast2_flags6; ?>
			<?php }?>
			<?php if ($action2_type == 12) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action2_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель суммона"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "Длительность призыва"; ?>:</td><td><input name="action2_param3" type="text" value="<?php echo $action2_param3; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($action2_type == 13) {?>
			<tr><td><?php echo "Процент увеличения (от -100 до +100)"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> %</td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 14) {?>
			<tr><td><?php echo "Процент увеличения (от -100 до +100)"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> %</td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 15) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 16) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action2_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "SpellID для каста на НПС из первого параметра"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"> <?php $spell2=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action2_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action2_param2\" target=\"_blank\">$action2_param2 $spell2[spellname_loc8] $spell2[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param3">
					<option value="0" <?php if ($action2_param3 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param3 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param3 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param3 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param3 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param3 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param3 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php }?>
			<?php if ($action2_type == 17) {?>
			<tr><td><?php echo "Номер поля DATA"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Значение"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param3">
					<option value="0" <?php if ($action2_param3 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param3 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param3 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param3 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param3 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param3 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param3 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php }?>
			<?php if ($action2_type == 18) {?>
			<tr><td><?php echo "Флаг"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 19) {?>
			<tr><td><?php echo "Флаг"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 20) {?>
			<tr><td><?php echo "Если 0 - НПС прекратит мили атаку, в противном случае - продолжит/начнёт"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 21) {?>
			<tr><td><?php echo "Если 0 - НПС прекратит движение, в противном случае - продолжит/начнёт"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 22) {?>
			<tr><td><?php echo "Номер новой фазы"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 23) {?>
			<tr><td><?php echo "Число, на которое увеличится номер фазы"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 24) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action2_param1 = 0;
					$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 25) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action2_param1 = 0;
					$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 26) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 27) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "SpellID для симуляции каста"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"> <?php $spell2=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action2_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action2_param2\" target=\"_blank\">$action2_param2 $spell2[spellname_loc8] $spell2[rank_loc0]</a>";?></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 28) {?>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param1">
					<option value="0" <?php if ($action2_param1 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param1 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param1 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param1 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param1 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param1 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param1 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "SpellID, вызвавший ауру"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"> <?php $spell2=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action2_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action2_param2\" target=\"_blank\">$action2_param2 $spell2[spellname_loc8] $spell2[rank_loc0]</a>";?></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 29) {?>
			<tr><td><?php echo "Distance (???)"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Angle (???)"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 30) {?>
			<tr><td><?php echo "Фаза_1"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Фаза_2"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<tr><td><?php echo "Фаза_3"; ?>:</td><td><input name="action2_param3" type="text" value="<?php echo $action2_param3; ?>"></td>
			<?php }?>
			<?php if ($action2_type == 31) {?>
			<tr><td><?php echo "Минимальный номер фазы"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Максимальный номер фазы"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 32) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action2_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель суммона"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "id из creature_ai_summons"; ?>:</td><td><input name="action2_param3" type="text" value="<?php echo $action2_param3; ?>"></td>
			<?php }?>
			<?php if ($action2_type == 33) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action2_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 34) {?>
			<tr><td><?php echo "Поле"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Значение"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 35) {?>
			<tr><td><?php echo "Поле"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action2_param2">
					<option value="0" <?php if ($action2_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action2_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action2_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action2_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action2_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action2_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action2_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 36) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action2_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "model_id для Альянса (0) или Орды (1)"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
			<?php 	$action2_param3 = 0;}?>
			<?php if ($action2_type == 37) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action2_param1 = 0;
					$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 38) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action2_param1 = 0;
					$action2_param2 = 0;
					$action2_param3 = 0;}?>
					
<!-- Action 3 -->
			<tr><td><?php echo "Тип действия 3"; ?>:</td><td>
			<select name="action3_type">
				<option value="0" <?php if ($action3_type == 0) {?> selected <?php }?>>Ничего</option>
				<option value="1" <?php if ($action3_type == 1) {?> selected <?php }?>>Случайный текст</option>
				<option value="2" <?php if ($action3_type == 2) {?> selected <?php }?>>Сменить фракцию НПС</option>
				<option value="3" <?php if ($action3_type == 3) {?> selected <?php }?>>Сменить модель НПС</option>
				<option value="4" <?php if ($action3_type == 4) {?> selected <?php }?>>Проиграть SOUND</option>
				<option value="5" <?php if ($action3_type == 5) {?> selected <?php }?>>Проиграть эмоцию</option>
				<option value="9" <?php if ($action3_type == 9) {?> selected <?php }?>>Проиграть случайно SOUND</option>
				<option value="10" <?php if ($action3_type == 10) {?> selected <?php }?>>Проиграть случайно эмоцию</option>
				<option value="11" <?php if ($action3_type == 11) {?> selected <?php }?>>Каст спелла</option>
				<option value="12" <?php if ($action3_type == 12) {?> selected <?php }?>>Призыв НПС</option>
				<option value="13" <?php if ($action3_type == 13) {?> selected <?php }?>>Изменить угрозу для опред. цели</option>
				<option value="14" <?php if ($action3_type == 14) {?> selected <?php }?>>Изменить угрозу для всех целей</option>
				<option value="15" <?php if ($action3_type == 15) {?> selected <?php }?>>Explored для квеста</option>
				<option value="16" <?php if ($action3_type == 16) {?> selected <?php }?>>Засчитать каст спелла на НПС/ГО для цели</option>
				<option value="17" <?php if ($action3_type == 17) {?> selected <?php }?>>Изменить UNIT_FIELD</option>
				<option value="18" <?php if ($action3_type == 18) {?> selected <?php }?>>Изменить UNIT_FLAG</option>
				<option value="19" <?php if ($action3_type == 19) {?> selected <?php }?>>Убрать UNIT_FLAG</option>
				<option value="20" <?php if ($action3_type == 20) {?> selected <?php }?>>Авто melee атака</option>
				<option value="21" <?php if ($action3_type == 21) {?> selected <?php }?>>Движение НПС</option>
				<option value="22" <?php if ($action3_type == 22) {?> selected <?php }?>>Установить фазу</option>
				<option value="23" <?php if ($action3_type == 23) {?> selected <?php }?>>Повысить фазу</option>
				<option value="24" <?php if ($action3_type == 24) {?> selected <?php }?>>Уйти в эвейд</option>
				<option value="25" <?php if ($action3_type == 25) {?> selected <?php }?>>Побег с поля боя</option>
				<option value="26" <?php if ($action3_type == 26) {?> selected <?php }?>>Завершить квест для группы</option>
				<option value="27" <?php if ($action3_type == 27) {?> selected <?php }?>>Засчитать каст спелла на НПС/ГО для группы</option>
				<option value="28" <?php if ($action3_type == 28) {?> selected <?php }?>>Убрать с цели ауру от спелла</option>
				<option value="29" <?php if ($action3_type == 29) {?> selected <?php }?>>29 ACTION_T_RANGED_MOVEMENT</option>
				<option value="30" <?php if ($action3_type == 30) {?> selected <?php }?>>Установить фазу рандомно</option>
				<option value="31" <?php if ($action3_type == 31) {?> selected <?php }?>>Установить фазу в заданном параметре</option>
				<option value="32" <?php if ($action3_type == 32) {?> selected <?php }?>>Призыв НПС в опред. точку</option>
				<option value="33" <?php if ($action3_type == 33) {?> selected <?php }?>>Зачитать убийство опред. НПС для цели</option>
				<option value="34" <?php if ($action3_type == 34) {?> selected <?php }?>>34 ACTION_T_SET_INST_DATA</option>
				<option value="35" <?php if ($action3_type == 35) {?> selected <?php }?>>35 ACTION_T_SET_INST_DATA64</option>
				<option value="36" <?php if ($action3_type == 36) {?> selected <?php }?>>Изменить creature_template для НПС</option>
				<option value="37" <?php if ($action3_type == 37) {?> selected <?php }?>>Смерть НПС</option>
				<option value="38" <?php if ($action3_type == 38) {?> selected <?php }?>>Ввести всех игроков инста в бой</option>
			</select> <input type="submit"value="Дальше..."></td>
			</form>
			<?php if ($action3_type == 1) {?>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action3_param3" type="text" value="<?php echo $action3_param3; ?>"></td>
			<?php }?>
			<?php if ($action3_type == 2) {?>
			<tr><td><?php echo "FactionId из Faction.dbc. 0 для возврата оригинальной фракции"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 3) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action3_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "modelID, если первый параметр = 0"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 4) {?>
			<tr><td><?php echo "SoundID из DBC"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 5) {?>
			<tr><td><?php echo "EmoteID из DBC"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 9) {?>
			<tr><td><?php echo "SoundID_1 из DBC"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "SoundID_2 из DBC"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<tr><td><?php echo "SoundID_3 из DBC"; ?>:</td><td><input name="action3_param3" type="text" value="<?php echo $action3_param3; ?>"></td>
			<?php }?>
			<?php if ($action3_type == 10) {?>
			<tr><td><?php echo "EmoteID_1 из DBC"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "EmoteID_2 из DBC"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<tr><td><?php echo "EmoteID_3 из DBC"; ?>:</td><td><input name="action3_param3" type="text" value="<?php echo $action3_param3; ?>"></td>
			<?php }?>
			<?php if ($action3_type == 11) {?>
			<tr><td><?php echo "SpellID"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $spell3=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action3_param1\" target=\"_blank\">$action3_param1 $spell3[spellname_loc8] $spell3[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "Флаг каста"; ?>:</td>
				<td><input type="checkbox" name="cast3_flags1" value="1" <?php if ($action3_param3&1) {?>checked<?php }?>>Прерывает любой другой каст<br>
				<input type="checkbox" name="cast3_flags2" value="2" <?php if ($action3_param3&2) {?>checked<?php }?>>Инстант каст без требования маны/реагентов<br>
				<input type="checkbox" name="cast3_flags3" value="4" <?php if ($action3_param3&4) {?>checked<?php }?>>Каст если цель далеко или нет маны<br>
				<input type="checkbox" name="cast3_flags4" value="8" <?php if ($action3_param3&8) {?>checked<?php }?>>Запрет начала мили атаки<br>
				<input type="checkbox" name="cast3_flags5" value="16" <?php if ($action3_param3&16) {?>checked<?php }?>>Каст этого спелла целью<br>
				<input type="checkbox" name="cast3_flags6" value="32" <?php if ($action3_param3&32) {?>checked<?php }?>>Только если на цели нет ауры от этого спелла</td>
			<?php $action3_param3 = $cast3_flags1+$cast3_flags2+$cast3_flags3+$cast3_flags4+$cast3_flags5+$cast3_flags6; ?>
			<?php }?>
			<?php if ($action3_type == 12) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action3_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель суммона"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "Длительность призыва"; ?>:</td><td><input name="action3_param3" type="text" value="<?php echo $action3_param3; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($action3_type == 13) {?>
			<tr><td><?php echo "Процент увеличения (от -100 до +100)"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> %</td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 14) {?>
			<tr><td><?php echo "Процент увеличения (от -100 до +100)"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> %</td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 15) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 16) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action3_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "SpellID для каста на НПС из первого параметра"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"> <?php $spell3=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action3_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action3_param2\" target=\"_blank\">$action3_param2 $spell3[spellname_loc8] $spell3[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param3">
					<option value="0" <?php if ($action3_param3 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param3 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param3 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param3 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param3 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param3 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param3 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php }?>
			<?php if ($action3_type == 17) {?>
			<tr><td><?php echo "Номер поля DATA"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Значение"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param3">
					<option value="0" <?php if ($action3_param3 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param3 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param3 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param3 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param3 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param3 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param3 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php }?>
			<?php if ($action3_type == 18) {?>
			<tr><td><?php echo "Флаг"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 19) {?>
			<tr><td><?php echo "Флаг"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 20) {?>
			<tr><td><?php echo "Если 0 - НПС прекратит мили атаку, в противном случае - продолжит/начнёт"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 21) {?>
			<tr><td><?php echo "Если 0 - НПС прекратит движение, в противном случае - продолжит/начнёт"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 22) {?>
			<tr><td><?php echo "Номер новой фазы"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 23) {?>
			<tr><td><?php echo "Число, на которое увеличится номер фазы"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 24) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action3_param1 = 0;
					$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 25) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action3_param1 = 0;
					$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 26) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 27) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "SpellID для симуляции каста"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"> <?php $spell3=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action3_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action3_param2\" target=\"_blank\">$action3_param2 $spell3[spellname_loc8] $spell3[rank_loc0]</a>";?></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 28) {?>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param1">
					<option value="0" <?php if ($action3_param1 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param1 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param1 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param1 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param1 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param1 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param1 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "SpellID, вызвавший ауру"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"> <?php $spell3=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$action3_param2.""); echo "<a href=\"http://ru.wowhead.com/?spell=$action3_param2\" target=\"_blank\">$action3_param2 $spell3[spellname_loc8] $spell3[rank_loc0]</a>";?></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 29) {?>
			<tr><td><?php echo "Distance (???)"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Angle (???)"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 30) {?>
			<tr><td><?php echo "Фаза_1"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Фаза_2"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<tr><td><?php echo "Фаза_3"; ?>:</td><td><input name="action3_param3" type="text" value="<?php echo $action3_param3; ?>"></td>
			<?php }?>
			<?php if ($action3_type == 31) {?>
			<tr><td><?php echo "Минимальный номер фазы"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Максимальный номер фазы"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 32) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action3_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель суммона"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<tr><td><?php echo "id из creature_ai_summons"; ?>:</td><td><input name="action3_param3" type="text" value="<?php echo $action3_param3; ?>"></td>
			<?php }?>
			<?php if ($action3_type == 33) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action3_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 34) {?>
			<tr><td><?php echo "Поле"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Значение"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 35) {?>
			<tr><td><?php echo "Поле"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "Цель"; ?>:</td>
				<td><select name="action3_param2">
					<option value="0" <?php if ($action3_param2 == 0) {?> selected <?php }?>>Сам НПС (Self)</option>
					<option value="1" <?php if ($action3_param2 == 1) {?> selected <?php }?>>Текущая цель</option>
					<option value="2" <?php if ($action3_param2 == 2) {?> selected <?php }?>>Вторая цель в аггро-листе</option>
					<option value="3" <?php if ($action3_param2 == 3) {?> selected <?php }?>>Последний убитый</option>
					<option value="4" <?php if ($action3_param2 == 4) {?> selected <?php }?>>Случайная цель из аггро-листа</option>
					<option value="5" <?php if ($action3_param2 == 5) {?> selected <?php }?>>Случайная, только не первая по аггро</option>
					<?php if ($event_type == 4 || $event_type == 5 || $event_type == 6|| $event_type == 8 || $event_type == 10|| $event_type == 14) {?><option value="6" <?php if ($action3_param2 == 6) {?> selected <?php }?>>Тот, кто вызвал событие</option><?php }?>
				</select></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 36) {?>
			<tr><td><?php echo "entry из creature_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $crname = $dDB-> selectCell("SELECT `name_loc8` FROM `locales_creature` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?npc=$action3_param1\" target=\"_blank\">$crname</a>";?></td>
			<tr><td><?php echo "model_id для Альянса (0) или Орды (1)"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
			<?php 	$action3_param3 = 0;}?>
			<?php if ($action3_type == 37) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action3_param1 = 0;
					$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 38) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$action3_param1 = 0;
					$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<tr><td>Комментарий:</td><td><input name="comment" type="text" value="<?php echo $comment; ?>"></td>
			<tr><td></td><td><button value="1" name="done" type="submit">Готово!</button><button value="1" name="write" type="submit">Записать в файл</button></td>
		</table>
	</form>
</body>
</html>
<?php
if($done != '')
{
	$sql="REPLACE INTO `creature_ai_scripts` VALUES ('$id', '$creature_id', '$event_type', '$event_inverse_phase_mask', '$event_chance', '$event_flags', '$event_param1', '$event_param2', '$event_param3', '$event_param4', '$action1_type', '$action1_param1', '$action1_param2', '$action1_param3', '$action2_type', '$action2_param1', '$action2_param2', '$action2_param3', '$action3_type', '$action3_param1', '$action3_param2', '$action3_param3', '$comment');
UPDATE `creature_template` SET `AIName`='EventAI' WHERE `entry`='$creature_id';";
	echo "<hr><b>$lang_sqlquery:</b><br><TEXTAREA readonly rows=\"5\" cols=\"70\">$sql</TEXTAREA>";
	if ($write == 1)
	{
		$sql_save="$sql_name\nREPLACE INTO `creature_ai_scripts` VALUES ('$id','$creature_id','$event_type','$event_inverse_phase_mask','$event_chance','$event_flags','$event_param1','$event_param2','$event_param3','$event_param4','$action1_type','$action1_param1','$action1_param2','$action1_param3','$action2_type','$action2_param1','$action2_param2','$action2_param3','$action3_type','$action3_param1','$action3_param2','$action3_param3','$comment');
	UPDATE `creature_template` SET `AIName`='EventAI' WHERE `entry`='$creature_id';";
	$eventsql=fopen("easy_eventai.sql","a+");
	fputs($eventsql,"$sql_save\r\n");
	fclose($eventsql);
	@chmod("$eventsql", 0644);
	}
}
?>