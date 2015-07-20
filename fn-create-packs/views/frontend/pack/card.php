<div class="cardPlaceholder">
    <div class="card c<?php echo $c; ?> <?php echo $card->type; ?> "
         style="<?php /*left:<?php echo $left; ?>px; top:<?php echo $top; ?>px;*/ ?> opacity:<?php echo $opacity; ?>;">
		<span class="upper">
			<span class="commonName">
				<?php
                if (!empty($card->player_data['commonName'])) echo $card->player_data['commonName'];
                else echo $card->player_data['firstName'] . ' ' . $card->player_data['lastName'];
                ?>
			</span>
			<span class="rating"><?php echo $card->player_data['rating']; ?></span>
			<span class="position"><?php echo $card->player_data['position']; ?></span>
			<span class="left">
				<span class="clubFlag">
					<img src="<?php echo $card->player_data['clubFlag']; ?>" alt="Club Flag"/>
				</span>
				<span class="nationFlag">
					<img src="<?php echo $card->player_data['nationFlag']; ?>" alt="Nation Flag"/>
				</span>
			</span>
			<span class="right">
				<span class="avatar">
					<img src="<?php echo $card->player_data['avatar']; ?>"
                         alt="<?php echo $card->player_data['commonName']; ?>"/>
				</span>
			</span>
		</span>
		<span class="lower">
			<span class="left">
				<span class="pace"><?php echo $card->player_data['pace']; ?> PAC</span>
				<span class="shooting"><?php echo $card->player_data['shooting']; ?> SHO</span>
				<span class="passing"><?php echo $card->player_data['passing']; ?> PAS</span>
			</span>
			<span class="right">
				<span class="dribbling"><?php echo $card->player_data['dribbling']; ?> DRI</span>
				<span class="defending"><?php echo $card->player_data['defending']; ?> DEF</span>
				<span class="physical"><?php echo $card->player_data['physical']; ?> PHY</span>
			</span>
		</span>
    </div>
</div>