<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Vending Database</h2>
<p class="toggler"><a href="javascript:toggleSearchForm()">Search...</a></p>
<form action="<?php echo $this->url ?>" method="get" class="search-form">
	<?php echo $this->moduleActionFormInputs($params->get('module'), $params->get('action')) ?>
	<p>
		<label for="item_name">Item Name or ID:</label>
		<input type="text" name="item_name" id="item_name" value="<?php echo htmlspecialchars($params->get('item_name')) ?>" />
		<input type="submit" value="Search" />
		<input type="button" value="Reset" onclick="reload()" />
	</p>
</form>

<p><?php echo @$search_info ?></p>
<?php if ($chars): ?>
<?php echo $paginator->infoText() ?>

<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('merchant_name', 'Merchant') ?></th>
		<th><?php echo $paginator->sortableColumn('am.title', 'Shop') ?></th>
		<th>Position</th>
		<th colspan="2"><?php echo $paginator->sortableColumn('nameid', 'Item') ?></th>
		<th><?php echo $paginator->sortableColumn('amount', 'Amount') ?></th>
		<th><?php echo $paginator->sortableColumn('price', 'Price') ?></th>
		<th><?php echo $paginator->sortableColumn('refine', 'Refine') ?></th>
		<th><?php echo $paginator->sortableColumn('card0', 'Card(1)') ?></th>
		<th><?php echo $paginator->sortableColumn('card1', 'Card(2)') ?></th>
		<th><?php echo $paginator->sortableColumn('card2', 'Card(3)') ?></th>
		<th><?php echo $paginator->sortableColumn('card3', 'Card(4)') ?></th>
	</tr>
	<?php foreach ($chars as $char): ?>
	<?php
		if(is_null($char->id)) continue;
		$char->title2 = $char->title;
		$char->title = mb_substr($char->title,0, 10, "UTF-8")."...";
		
		$vvs = "";
		if ($char->card0 == 255 && intval($char->card1/1280) > 0)
		{
			for ($i = 0; $i < intval($char->card1/1280); $i++)
			{
				$vvs .= "Very ";
			}
			$vvs .= "Strong ";
			$vvs = "<span style='color: blue;'>{$vvs}</span> ";
		}	
	?>
	<tr>
		<td>
			<?php if ($auth->actionAllowed('character', 'view') && $auth->allowedToViewCharacter): ?>
			<?php echo $this->linkToCharacter($char->char_id, $char->merchant_name) ?>
			<?php else: ?>
			<?php echo htmlspecialchars($char->merchant_name) ?>
			<?php endif ?>
		</td>
		<td title="<?php echo htmlspecialchars($char->title2) ?>">
			<?php echo htmlspecialchars($char->title) ?>
		</td>
		<td>
			<?php echo htmlspecialchars($char->last_map)." ".htmlspecialchars($char->last_x).",".htmlspecialchars($char->last_y) ?>
		</td>
		<?php if ($icon=$this->iconImage($char->nameid)) ?>
		<td width="24"><img src="<?php echo htmlspecialchars($icon); ?>?nocache=<?php echo rand(); ?>" /></td>
		<td>
			<?php
				$nick = "";
				if($char->card0 == 254) {  $nick_just = get_char_name($char->card2,$server); $nick = "<span style='color: blue;'>{$nick_just}'s</span> "; }
				echo $nick.$vvs;
				echo $this->linkToItem($char->nameid,get_item_name($char->nameid,$server));
			?>
		</td>
		<td>
			<?php echo number_format($char->amount) ?>
		</td>
		<td>
			<?php echo number_format($char->price) ?>
		</td>
		<td>
			<?php echo htmlspecialchars(refine_lvl($char->refine)) ?>
		</td>
		<td>
			<?php echo $this->linkToItem($char->card0, get_item_name($char->card0,$server)) ?>
		</td>
		<td>
			<?php echo $this->linkToItem($char->card1, get_item_name($char->card1,$server)) ?>
		</td>
		<td>
			<?php echo $this->linkToItem($char->card2, get_item_name(($char->card2 > 255 && $char->card0 != 254) ? $char->card2:0,$server)) ?>
		</td>
		<td>
			<?php echo $this->linkToItem($char->card3, get_item_name($char->card3,$server)) ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>Nothing was found on <?php echo htmlspecialchars($server->serverName) ?>. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>