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
$ai_text_loc			= '8'; // locale. Defaul: 8 (Russian locale)
$version		= '0.1.4'; // script version

include ("lang.php");
include_once ("DbSimple/Generic.php");
$dDB = DbSimple_Generic::connect("mysql://root:13589436@127.0.0.1/ytdb-w");
mysql_query('SET NAMES utf8;');

// запоминаем данные если они уже были введены
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

// заполняем данные, если используется поиск
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
				<option value="0" <?php if ($event_type == 0) {?> selected <?php }?>>0_По таймеру в бою</option>
				<option value="1" <?php if ($event_type == 1) {?> selected <?php }?>>1_По таймеру вне боя</option>
				<option value="2" <?php if ($event_type == 2) {?> selected <?php }?>>2_При значении HP</option>
				<option value="3" <?php if ($event_type == 3) {?> selected <?php }?>>3_При значении MP</option>
				<option value="4" <?php if ($event_type == 4) {?> selected <?php }?>>4_При зааггривании</option>
				<option value="5" <?php if ($event_type == 5) {?> selected <?php }?>>5_При убийстве цели</option>
				<option value="6" <?php if ($event_type == 6) {?> selected <?php }?>>6_При смерти</option>
				<option value="7" <?php if ($event_type == 7) {?> selected <?php }?>>7_При уходе в эвейд</option>
				<option value="8" <?php if ($event_type == 8) {?> selected <?php }?>>8_По урону спеллом</option>
				<option value="9" <?php if ($event_type == 9) {?> selected <?php }?>>9_При дистанции</option>
				<option value="10" <?php if ($event_type == 10) {?> selected <?php }?>>10_При появлении в зоне LOS</option>
				<option value="11" <?php if ($event_type == 11) {?> selected <?php }?>>11_При спавне</option>
				<option value="12" <?php if ($event_type == 12) {?> selected <?php }?>>12_При значении НР цели</option>
				<option value="13" <?php if ($event_type == 13) {?> selected <?php }?>>13_Если цель кастует</option>
				<option value="14" <?php if ($event_type == 14) {?> selected <?php }?>>14_При значении HP дружественной цели</option>
				<option value="15" <?php if ($event_type == 15) {?> selected <?php }?>>15_Если дружественная цель под контролем</option>
				<option value="16" <?php if ($event_type == 16) {?> selected <?php }?>>16_Если теряет бафф</option>
				<option value="17" <?php if ($event_type == 17) {?> selected <?php }?>>17_При спавне НПС</option>
				<option value="21" <?php if ($event_type == 21) {?> selected <?php }?>>21_По возвращению в точку спавна</option>
				<option value="22" <?php if ($event_type == 22) {?> selected <?php }?>>22_При получении эмоции</option>
			</select> <input type="submit"value="Дальше..."></td>
			<?php if ($event_type == 0) {?>
			<tr><td><?php echo "Min время до срабатывания"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до срабатывания"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 1) {?>
			<tr><td><?php echo "Min время до срабатывания"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до срабатывания"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 2) {?>
			<tr><td><?php echo "Max значение HP"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> %</td>
			<tr><td><?php echo "Min значение HP"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> %</td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 3) {?>
			<tr><td><?php echo "Max значение MP"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> %</td>
			<tr><td><?php echo "Min значение MP"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> %</td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 4) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$event_param1 = 0;
					$event_param2 = 0;
					$event_param3 = 0;
					$event_param4 = 0;}?>
			<?php if ($event_type == 5) {?>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
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
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 9) {?>
			<tr><td><?php echo "Минимальная дистанция до цели"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Максимальная дистанция до цели"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 10) {?>
			<tr><td><?php echo "Фракция цели"; ?>:</td><td><input type="radio" name="event_param1" value="0" <?php if ($event_param1==0) echo "checked";?>> Враждебная<Br>
   <input type="radio" name="event_param1" value="1"<?php if ($event_param1==1) echo "checked";?>> Дружественная<Br></td></td>
			<tr><td><?php echo "Максимальная дистанция до цели"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 11) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$event_param1 = 0;
					$event_param2 = 0;
					$event_param3 = 0;
					$event_param4 = 0;}?>
			<?php if ($event_type == 12) {?>
			<tr><td><?php echo "Max значение HP"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> %</td>
			<tr><td><?php echo "Min значение HP"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> %</td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 13) {?>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 14) {?>
			<tr><td><?php echo "Дефицит HP друж. цели"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> %</td>
			<tr><td><?php echo "Радиус действия"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 15) {?>
			<tr><td><?php echo "Тип диспелла"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Радиус"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 16) {?>
			<tr><td><?php echo "SpellID, дающий ауру"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"> <?php $spell=$dDB-> selectRow("SELECT * FROM `easyeventai_spell` WHERE `SpellID` = ".$event_param1.""); echo "<a href=\"http://ru.wowhead.com/?spell=$event_param1\" target=\"_blank\">$event_param1 $spell[spellname_loc8] $spell[rank_loc0]</a>";?></td>
			<tr><td><?php echo "Радиус"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"></td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 17) {?>
			<tr><td><?php echo "creature_id"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Min время до повтора"; ?>:</td><td><input name="event_param2" type="text" value="<?php echo $event_param2; ?>"> милисекунд</td>
			<tr><td><?php echo "Max время до повтора"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"> милисекунд</td>
			<?php }?>
			<?php if ($event_type == 21) {?>
			<tr><td>!!!</td><td>Дополнительные параметры не используются</td><tr>
			<?php 	$event_param1 = 0;
					$event_param2 = 0;
					$event_param3 = 0;
					$event_param4 = 0;}?>
			<?php if ($event_type == 22) {?>
			<tr><td><?php echo "ID эмоции"; ?>:</td><td><input name="event_param1" type="text" value="<?php echo $event_param1; ?>"></td>
			<tr><td><?php echo "Условие"; ?>:</td><td><select name="event_param2">
				<option value="0" <?php if ($event_param2 == 0) {?> selected <?php }?>>0_Нет условия</option>
				<option value="1" <?php if ($event_param2 == 1) {?> selected <?php }?>>1_Наличие ауры от спелла</option>
				<option value="2" <?php if ($event_param2 == 2) {?> selected <?php }?>>2_Наличие предмета</option>
				<option value="3" <?php if ($event_param2 == 3) {?> selected <?php }?>>3_Одет предмет</option>
				<option value="4" <?php if ($event_param2 == 4) {?> selected <?php }?>>4_Зона</option>
				<option value="5" <?php if ($event_param2 == 5) {?> selected <?php }?>>5_Репутация</option>
				<option value="6" <?php if ($event_param2 == 6) {?> selected <?php }?>>6_Фракция</option>
				<option value="7" <?php if ($event_param2 == 7) {?> selected <?php }?>>7_Умение (Skill)</option>
				<option value="8" <?php if ($event_param2 == 8) {?> selected <?php }?>>8_Выполнен квест</option>
				<option value="9" <?php if ($event_param2 == 9) {?> selected <?php }?>>9_Взят квест</option>
				<option value="12" <?php if ($event_param2 == 12) {?> selected <?php }?>>12_Событие</option></select></td>
			<tr><td><?php echo "Парметр_1 условия"; ?>:</td><td><input name="event_param3" type="text" value="<?php echo $event_param3; ?>"></td>
			<tr><td><?php echo "Парметр_2 условия"; ?>:</td><td><input name="event_param4" type="text" value="<?php echo $event_param4; ?>"></td>
			<?php }?>
			<tr><td><?php echo "Тип действия 1"; ?>:</td><td>
			<select name="action1_type">
				<option value="0" <?php if ($action1_type == 0) {?> selected <?php }?>>0_Ничего</option>
				<option value="1" <?php if ($action1_type == 1) {?> selected <?php }?>>1_Случайный текст</option>
				<option value="2" <?php if ($action1_type == 2) {?> selected <?php }?>>2_Сменить фракцию НПС</option>
				<option value="3" <?php if ($action1_type == 3) {?> selected <?php }?>>3_Сменить модель НПС</option>
				<option value="4" <?php if ($action1_type == 4) {?> selected <?php }?>>4_Проиграть SOUND</option>
				<option value="5" <?php if ($action1_type == 5) {?> selected <?php }?>>5_Проиграть эмоцию</option>
				<option value="9" <?php if ($action1_type == 9) {?> selected <?php }?>>9_Проиграть случайно SOUND</option>
				<option value="10" <?php if ($action1_type == 10) {?> selected <?php }?>>10_Проиграть случайно эмоцию</option>
				<option value="11" <?php if ($action1_type == 11) {?> selected <?php }?>>11_Каст спелла</option>
				<option value="12" <?php if ($action1_type == 12) {?> selected <?php }?>>12_Призыв НПС</option>
				<option value="13" <?php if ($action1_type == 13) {?> selected <?php }?>>13_Изменить угрозу для опред. цели</option>
				<option value="14" <?php if ($action1_type == 14) {?> selected <?php }?>>14_Изменить угрозу для всех целей</option>
				<option value="15" <?php if ($action1_type == 15) {?> selected <?php }?>>15_Explored для квеста</option>
				<option value="16" <?php if ($action1_type == 16) {?> selected <?php }?>>16_Засчитать каст спелла на НПС/ГО для цели</option>
				<option value="17" <?php if ($action1_type == 17) {?> selected <?php }?>>17_Изменить UNIT_FIELD</option>
				<option value="18" <?php if ($action1_type == 18) {?> selected <?php }?>>18_Изменить UNIT_FLAG</option>
				<option value="19" <?php if ($action1_type == 19) {?> selected <?php }?>>19_Убрать UNIT_FLAG</option>
				<option value="20" <?php if ($action1_type == 20) {?> selected <?php }?>>20_Авто melee атака</option>
				<option value="21" <?php if ($action1_type == 21) {?> selected <?php }?>>21_Движение НПС</option>
				<option value="22" <?php if ($action1_type == 22) {?> selected <?php }?>>22_Установить фазу</option>
				<option value="23" <?php if ($action1_type == 23) {?> selected <?php }?>>23_Повысить фазу</option>
				<option value="24" <?php if ($action1_type == 24) {?> selected <?php }?>>24_Уйти в эвейд</option>
				<option value="25" <?php if ($action1_type == 25) {?> selected <?php }?>>25_Побег с поля боя</option>
				<option value="26" <?php if ($action1_type == 26) {?> selected <?php }?>>26_Завершить квест для группы</option>
				<option value="27" <?php if ($action1_type == 27) {?> selected <?php }?>>27_Засчитать каст спелла на НПС/ГО для группы</option>
				<option value="28" <?php if ($action1_type == 28) {?> selected <?php }?>>28_Убрать с цели ауру от спелла</option>
				<option value="29" <?php if ($action1_type == 29) {?> selected <?php }?>>29_Удалиться от цели</option>
				<option value="30" <?php if ($action1_type == 30) {?> selected <?php }?>>30_Установить фазу рандомно</option>
				<option value="31" <?php if ($action1_type == 31) {?> selected <?php }?>>31_Установить фазу в заданном параметре</option>
				<option value="32" <?php if ($action1_type == 32) {?> selected <?php }?>>32_Призыв НПС в опред. точку</option>
				<option value="33" <?php if ($action1_type == 33) {?> selected <?php }?>>33_Зачитать убийство опред. НПС для цели</option>
				<option value="34" <?php if ($action1_type == 34) {?> selected <?php }?>>34_ACTION_T_SET_INST_DATA</option>
				<option value="35" <?php if ($action1_type == 35) {?> selected <?php }?>>35_ACTION_T_SET_INST_DATA64</option>
				<option value="36" <?php if ($action1_type == 36) {?> selected <?php }?>>36_Изменить creature_template для НПС</option>
				<option value="37" <?php if ($action1_type == 37) {?> selected <?php }?>>37_Смерть НПС</option>
				<option value="38" <?php if ($action1_type == 38) {?> selected <?php }?>>38_Ввести всех игроков инста в бой</option>
			</select> <input type="submit"value="Дальше..."></td>
			<?php if ($action1_type == 1) {?>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action1_param1.""); echo $text;?></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action1_param2.""); echo $text;?></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action1_param3" type="text" value="<?php echo $action1_param3; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action1_param3.""); echo $text;?></td>
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
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action1_param1\" target=\"_blank\">$action1_param1 $quest</a>";?></td>
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
			<tr><td><?php echo "Начать или прекратить мили-атаку"; ?>:</td><td><input type="radio" name="action1_param1" value="0" <?php if ($action1_param1==0) echo "checked";?>> Прекратить мили-атаку<Br>
   <input type="radio" name="action1_param1" value="1"<?php if ($action1_param1==1) echo "checked";?>> Начать/продолжить мили-атаку<Br></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 21) {?>
			<tr><td><?php echo "Начать или прекратить движение"; ?>:</td><td><input type="radio" name="action1_param1" value="0" <?php if ($action1_param1==0) echo "checked";?>> Прекратить движение<Br>
   <input type="radio" name="action1_param1" value="1"<?php if ($action1_param1==1) echo "checked";?>> Начать/продолжить движение<Br></td>
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
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action1_param1\" target=\"_blank\">$action1_param1 $quest</a>";?></td>
			<?php 	$action1_param2 = 0;
					$action1_param3 = 0;}?>
			<?php if ($action1_type == 27) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action1_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action1_param1\" target=\"_blank\">$action1_param1 $quest</a>";?></td>
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
			<tr><td><?php echo "На расстояние"; ?>:</td><td><input name="action1_param1" type="text" value="<?php echo $action1_param1; ?>"></td>
			<tr><td><?php echo "По углом"; ?>:</td><td><input name="action1_param2" type="text" value="<?php echo $action1_param2; ?>"></td>
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
			<tr><td><?php echo "Использовать"; ?>:</td><td><input type="radio" name="action1_param2" value="0" <?php if ($action1_param2==0) echo "checked";?>> modelid_A (для Альянса)<Br>
   <input type="radio" name="action1_param2" value="1"<?php if ($action1_param2==1) echo "checked";?>> modelid_H (для Орды)<Br></td>
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
				<option value="0" <?php if ($action2_type == 0) {?> selected <?php }?>>0_Ничего</option>
				<option value="1" <?php if ($action2_type == 1) {?> selected <?php }?>>1_Случайный текст</option>
				<option value="2" <?php if ($action2_type == 2) {?> selected <?php }?>>2_Сменить фракцию НПС</option>
				<option value="3" <?php if ($action2_type == 3) {?> selected <?php }?>>3_Сменить модель НПС</option>
				<option value="4" <?php if ($action2_type == 4) {?> selected <?php }?>>4_Проиграть SOUND</option>
				<option value="5" <?php if ($action2_type == 5) {?> selected <?php }?>>5_Проиграть эмоцию</option>
				<option value="9" <?php if ($action2_type == 9) {?> selected <?php }?>>9_Проиграть случайно SOUND</option>
				<option value="10" <?php if ($action2_type == 10) {?> selected <?php }?>>10_Проиграть случайно эмоцию</option>
				<option value="11" <?php if ($action2_type == 11) {?> selected <?php }?>>11_Каст спелла</option>
				<option value="12" <?php if ($action2_type == 12) {?> selected <?php }?>>12_Призыв НПС</option>
				<option value="13" <?php if ($action2_type == 13) {?> selected <?php }?>>13_Изменить угрозу для опред. цели</option>
				<option value="14" <?php if ($action2_type == 14) {?> selected <?php }?>>14_Изменить угрозу для всех целей</option>
				<option value="15" <?php if ($action2_type == 15) {?> selected <?php }?>>15_Explored для квеста</option>
				<option value="16" <?php if ($action2_type == 16) {?> selected <?php }?>>16_Засчитать каст спелла на НПС/ГО для цели</option>
				<option value="17" <?php if ($action2_type == 17) {?> selected <?php }?>>17_Изменить UNIT_FIELD</option>
				<option value="18" <?php if ($action2_type == 18) {?> selected <?php }?>>18_Изменить UNIT_FLAG</option>
				<option value="19" <?php if ($action2_type == 19) {?> selected <?php }?>>19_Убрать UNIT_FLAG</option>
				<option value="20" <?php if ($action2_type == 20) {?> selected <?php }?>>20_Авто melee атака</option>
				<option value="21" <?php if ($action2_type == 21) {?> selected <?php }?>>21_Движение НПС</option>
				<option value="22" <?php if ($action2_type == 22) {?> selected <?php }?>>22_Установить фазу</option>
				<option value="23" <?php if ($action2_type == 23) {?> selected <?php }?>>23_Повысить фазу</option>
				<option value="24" <?php if ($action2_type == 24) {?> selected <?php }?>>24_Уйти в эвейд</option>
				<option value="25" <?php if ($action2_type == 25) {?> selected <?php }?>>25_Побег с поля боя</option>
				<option value="26" <?php if ($action2_type == 26) {?> selected <?php }?>>26_Завершить квест для группы</option>
				<option value="27" <?php if ($action2_type == 27) {?> selected <?php }?>>27_Засчитать каст спелла на НПС/ГО для группы</option>
				<option value="28" <?php if ($action2_type == 28) {?> selected <?php }?>>28_Убрать с цели ауру от спелла</option>
				<option value="29" <?php if ($action2_type == 29) {?> selected <?php }?>>29_Удалиться от цели</option>
				<option value="30" <?php if ($action2_type == 30) {?> selected <?php }?>>30_Установить фазу рандомно</option>
				<option value="31" <?php if ($action2_type == 31) {?> selected <?php }?>>31_Установить фазу в заданном параметре</option>
				<option value="32" <?php if ($action2_type == 32) {?> selected <?php }?>>32_Призыв НПС в опред. точку</option>
				<option value="33" <?php if ($action2_type == 33) {?> selected <?php }?>>33_Зачитать убийство опред. НПС для цели</option>
				<option value="34" <?php if ($action2_type == 34) {?> selected <?php }?>>34_ACTION_T_SET_INST_DATA</option>
				<option value="35" <?php if ($action2_type == 35) {?> selected <?php }?>>35_ACTION_T_SET_INST_DATA64</option>
				<option value="36" <?php if ($action2_type == 36) {?> selected <?php }?>>36_Изменить creature_template для НПС</option>
				<option value="37" <?php if ($action2_type == 37) {?> selected <?php }?>>37_Смерть НПС</option>
				<option value="38" <?php if ($action2_type == 38) {?> selected <?php }?>>38_Ввести всех игроков инста в бой</option>
			</select> <input type="submit"value="Дальше..."></td>
			<?php if ($action2_type == 1) {?>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action2_param2.""); echo $text;?></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action2_param2.""); echo $text;?></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action2_param3" type="text" value="<?php echo $action2_param3; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action2_param3.""); echo $text;?></td>
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
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action2_param1\" target=\"_blank\">$action2_param1 $quest</a>";?></td>
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
			<tr><td><?php echo "Начать или прекратить мили-атаку"; ?>:</td><td><input type="radio" name="action2_param1" value="0" <?php if ($action2_param1==0) echo "checked";?>> Прекратить мили-атаку<Br>
   <input type="radio" name="action2_param1" value="1"<?php if ($action2_param1==1) echo "checked";?>> Начать/продолжить мили-атаку<Br></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 21) {?>
			<tr><td><?php echo "Начать или прекратить движение"; ?>:</td><td><input type="radio" name="action2_param1" value="0" <?php if ($action2_param1==0) echo "checked";?>> Прекратить движение<Br>
   <input type="radio" name="action2_param1" value="1"<?php if ($action2_param1==1) echo "checked";?>> Начать/продолжить движение<Br></td>
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
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action2_param1\" target=\"_blank\">$action2_param1 $quest</a>";?></td>
			<?php 	$action2_param2 = 0;
					$action2_param3 = 0;}?>
			<?php if ($action2_type == 27) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action2_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action2_param1\" target=\"_blank\">$action2_param1 $quest</a>";?></td>
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
			<tr><td><?php echo "На расстояние"; ?>:</td><td><input name="action2_param1" type="text" value="<?php echo $action2_param1; ?>"></td>
			<tr><td><?php echo "По углом"; ?>:</td><td><input name="action2_param2" type="text" value="<?php echo $action2_param2; ?>"></td>
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
			<tr><td><?php echo "Использовать"; ?>:</td><td><input type="radio" name="action2_param2" value="0" <?php if ($action2_param2==0) echo "checked";?>> modelid_A (для Альянса)<Br>
   <input type="radio" name="action2_param2" value="1"<?php if ($action2_param2==1) echo "checked";?>> modelid_H (для Орды)<Br></td></td>
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
				<option value="0" <?php if ($action3_type == 0) {?> selected <?php }?>>0_Ничего</option>
				<option value="1" <?php if ($action3_type == 1) {?> selected <?php }?>>1_Случайный текст</option>
				<option value="2" <?php if ($action3_type == 2) {?> selected <?php }?>>2_Сменить фракцию НПС</option>
				<option value="3" <?php if ($action3_type == 3) {?> selected <?php }?>>3_Сменить модель НПС</option>
				<option value="4" <?php if ($action3_type == 4) {?> selected <?php }?>>4_Проиграть SOUND</option>
				<option value="5" <?php if ($action3_type == 5) {?> selected <?php }?>>5_Проиграть эмоцию</option>
				<option value="9" <?php if ($action3_type == 9) {?> selected <?php }?>>9_Проиграть случайно SOUND</option>
				<option value="10" <?php if ($action3_type == 10) {?> selected <?php }?>>10_Проиграть случайно эмоцию</option>
				<option value="11" <?php if ($action3_type == 11) {?> selected <?php }?>>11_Каст спелла</option>
				<option value="12" <?php if ($action3_type == 12) {?> selected <?php }?>>12_Призыв НПС</option>
				<option value="13" <?php if ($action3_type == 13) {?> selected <?php }?>>13_Изменить угрозу для опред. цели</option>
				<option value="14" <?php if ($action3_type == 14) {?> selected <?php }?>>14_Изменить угрозу для всех целей</option>
				<option value="15" <?php if ($action3_type == 15) {?> selected <?php }?>>15_Explored для квеста</option>
				<option value="16" <?php if ($action3_type == 16) {?> selected <?php }?>>16_Засчитать каст спелла на НПС/ГО для цели</option>
				<option value="17" <?php if ($action3_type == 17) {?> selected <?php }?>>17_Изменить UNIT_FIELD</option>
				<option value="18" <?php if ($action3_type == 18) {?> selected <?php }?>>18_Изменить UNIT_FLAG</option>
				<option value="19" <?php if ($action3_type == 19) {?> selected <?php }?>>19_Убрать UNIT_FLAG</option>
				<option value="20" <?php if ($action3_type == 20) {?> selected <?php }?>>20_Авто melee атака</option>
				<option value="21" <?php if ($action3_type == 21) {?> selected <?php }?>>21_Движение НПС</option>
				<option value="22" <?php if ($action3_type == 22) {?> selected <?php }?>>22_Установить фазу</option>
				<option value="23" <?php if ($action3_type == 23) {?> selected <?php }?>>23_Повысить фазу</option>
				<option value="24" <?php if ($action3_type == 24) {?> selected <?php }?>>24_Уйти в эвейд</option>
				<option value="25" <?php if ($action3_type == 25) {?> selected <?php }?>>25_Побег с поля боя</option>
				<option value="26" <?php if ($action3_type == 26) {?> selected <?php }?>>26_Завершить квест для группы</option>
				<option value="27" <?php if ($action3_type == 27) {?> selected <?php }?>>27_Засчитать каст спелла на НПС/ГО для группы</option>
				<option value="28" <?php if ($action3_type == 28) {?> selected <?php }?>>28_Убрать с цели ауру от спелла</option>
				<option value="29" <?php if ($action3_type == 29) {?> selected <?php }?>>29_Удалиться от цели</option>
				<option value="30" <?php if ($action3_type == 30) {?> selected <?php }?>>30_Установить фазу рандомно</option>
				<option value="31" <?php if ($action3_type == 31) {?> selected <?php }?>>31_Установить фазу в заданном параметре</option>
				<option value="32" <?php if ($action3_type == 32) {?> selected <?php }?>>32_Призыв НПС в опред. точку</option>
				<option value="33" <?php if ($action3_type == 33) {?> selected <?php }?>>33_Зачитать убийство опред. НПС для цели</option>
				<option value="34" <?php if ($action3_type == 34) {?> selected <?php }?>>34_ACTION_T_SET_INST_DATA</option>
				<option value="35" <?php if ($action3_type == 35) {?> selected <?php }?>>35_ACTION_T_SET_INST_DATA64</option>
				<option value="36" <?php if ($action3_type == 36) {?> selected <?php }?>>36_Изменить creature_template для НПС</option>
				<option value="37" <?php if ($action3_type == 37) {?> selected <?php }?>>37_Смерть НПС</option>
				<option value="38" <?php if ($action3_type == 38) {?> selected <?php }?>>38_Ввести всех игроков инста в бой</option>
			</select> <input type="submit"value="Дальше..."></td>
			</form>
			<?php if ($action3_type == 1) {?>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action3_param1.""); echo $text;?></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action3_param2.""); echo $text;?></td>
			<tr><td><?php echo "entry из creature_ai_texts"; ?>:</td><td><input name="action3_param3" type="text" value="<?php echo $action3_param3; ?>"> <?php $text = $dDB-> selectCell("SELECT `content_loc".$ai_text_loc."` FROM `creature_ai_texts` WHERE `entry` = ".$action3_param3.""); echo $text;?></td>
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
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action3_param1\" target=\"_blank\">$action3_param1 $quest</a>";?></td>
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
			<tr><td><?php echo "Начать или прекратить мили-атаку"; ?>:</td><td><input type="radio" name="action3_param1" value="0" <?php if ($action3_param1==0) echo "checked";?>> Прекратить мили-атаку<Br>
   <input type="radio" name="action3_param1" value="1"<?php if ($action3_param1==1) echo "checked";?>> Начать/продолжить мили-атаку<Br></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 21) {?>
			<tr><td><?php echo "Начать или прекратить движение"; ?>:</td><td><input type="radio" name="action3_param1" value="0" <?php if ($action3_param1==0) echo "checked";?>> Прекратить движение<Br>
   <input type="radio" name="action3_param1" value="1"<?php if ($action3_param1==1) echo "checked";?>> Начать/продолжить движение<Br></td>
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
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action3_param1\" target=\"_blank\">$action3_param1 $quest</a>";?></td>
			<?php 	$action3_param2 = 0;
					$action3_param3 = 0;}?>
			<?php if ($action3_type == 27) {?>
			<tr><td><?php echo "entry из quest_template"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"> <?php $quest=$dDB-> selectCell("SELECT `Title_loc8` FROM `locales_quest` WHERE `entry` = ".$action3_param1.""); echo "<a href=\"http://ru.wowhead.com/?quest=$action3_param1\" target=\"_blank\">$action3_param1 $quest</a>";?></td>
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
			<tr><td><?php echo "На расстояние"; ?>:</td><td><input name="action3_param1" type="text" value="<?php echo $action3_param1; ?>"></td>
			<tr><td><?php echo "По углом"; ?>:</td><td><input name="action3_param2" type="text" value="<?php echo $action3_param2; ?>"></td>
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
			<tr><td><?php echo "Использовать"; ?>:</td><td><input type="radio" name="action3_param2" value="0" <?php if ($action3_param2==0) echo "checked";?>> modelid_A (для Альянса)<Br>
   <input type="radio" name="action3_param2" value="1"<?php if ($action3_param2==1) echo "checked";?>> modelid_H (для Орды)<Br></td>
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
	// проверка на ошибки
	switch ($event_type)
	{
		case 0:
		case 1:
		case 2:
		case 3:
		case 9:
		case 10:
		case 12:
		case 13:
		case 14:
		case 15:
		case 16:
		{
			if ($event_param3 > $event_param4)
			{
				$error = '<b>Ошибка праметров события:<br>Значение Min больше значения Max!</b>';
				echo $error;
				return;
			}
		}
		case 2:
		case 3:
		case 12:
		{
			if ($event_param2 > $event_param1)
			{
				$error = '<b>Ошибка праметров события:<br>Значение Min больше значения Max!</b>';
				echo $error;
				return;
			}
		}
		case 8:
		{
			if ($event_param1 > 0 && $event_param2 != -1)
			{
				$error = '<b>Ошибка праметров события:<br>Если используется SpellID, то Spell School должно быть равно "-1", eсли используется Spell School, то SpellID должно быть равно "0"</b>';
				echo $error;
				return;
			}
			else if ($event_param3 > $event_param4)
			{
				$error = '<b>Ошибка праметров события:<br>Значение Min больше значения Max!</b>';
				echo $error;
				return;
			}
		}
		case 5:
		{
			if ($event_param1 > $event_param2)
			{
				$error = '<b>Ошибка праметров события:<br>Значение Min больше значения Max!</b>';
				echo $error;
				return;
			}
		}
		
	}
	// вывод запроса на страницу
	$sql="REPLACE INTO `creature_ai_scripts` VALUES ('$id', '$creature_id', '$event_type', '$event_inverse_phase_mask', '$event_chance', '$event_flags', '$event_param1', '$event_param2', '$event_param3', '$event_param4', '$action1_type', '$action1_param1', '$action1_param2', '$action1_param3', '$action2_type', '$action2_param1', '$action2_param2', '$action2_param3', '$action3_type', '$action3_param1', '$action3_param2', '$action3_param3', '$comment');
UPDATE `creature_template` SET `AIName`='EventAI' WHERE `entry`='$creature_id';";
	echo "<hr><b>$lang_sqlquery:</b><br><TEXTAREA readonly rows=\"5\" cols=\"70\">$sql</TEXTAREA>";
	if ($write == 1)
	{
		// вывод запроса в файл, если выбрали "Записать"
		$sql_save="$sql_name\nREPLACE INTO `creature_ai_scripts` VALUES ('$id','$creature_id','$event_type','$event_inverse_phase_mask','$event_chance','$event_flags','$event_param1','$event_param2','$event_param3','$event_param4','$action1_type','$action1_param1','$action1_param2','$action1_param3','$action2_type','$action2_param1','$action2_param2','$action2_param3','$action3_type','$action3_param1','$action3_param2','$action3_param3','$comment');
	UPDATE `creature_template` SET `AIName`='EventAI' WHERE `entry`='$creature_id';";
	$eventsql=fopen("easy_eventai.sql","a+");
	fputs($eventsql,"$sql_save\r\n");
	fclose($eventsql);
	@chmod("$eventsql", 0644);
	}
}
?>