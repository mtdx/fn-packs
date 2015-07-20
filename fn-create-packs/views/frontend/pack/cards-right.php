<div class="cardsRight">
    <?php
    /*  $top = 10;
     $left = 0; */
    $opacity = 0;
    $i = 0;
    $jscards = array();
    foreach ($pack->cards as $card) {
        $jscards[] = $i + 1;
        $c = $i + 1;
        include FNCP_DIR_PATH . 'views/frontend/pack/card.php';
        /* $left += 180; 
        if (($i + 1) % 5 == 0) {
            $top += 300;
            $left = 0;
        } */
        $i++;
    }
    ?>
</div>