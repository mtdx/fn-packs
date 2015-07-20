<div class="detailsLeft">
    <h1>You are opening the <?php echo $pack->fullname; ?></h1>

    <h2>Coins: <span data-coins="<?php echo $pack->stdValue; ?>" class="coins"><?php echo $pack->stdValue; ?></span>
    </h2>
    <?php
    if (!empty($flash_card)):
        $opacity = 1;
        $card = $flash_card;
        $c = 'm';
        ?>
        <div id="MFContainerPlaceholder"></div>
        <div id="MFContainer" class="MFContainer">
            <?php include FNCP_DIR_PATH . 'views/frontend/pack/card.php'; ?>
            <img class="goldCircle" src="<?php echo FNCP_DIR_URL; ?>/views/img/bgs/gold-circle.png"/>
        </div>
    <?php endif; ?>
</div>