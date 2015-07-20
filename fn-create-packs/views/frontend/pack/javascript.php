<script type="text/javascript">
    var rotation = 0;
    var posX;
    var coinCounter = 100;
    var coinTimer;
    var cardTimer;
    var rotationTimer;
    var cards = [<?php echo implode(',', $jscards); ?>];
    var finalAmt = <?php echo $pack->value ?>;
</script>