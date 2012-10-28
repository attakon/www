<?php
    function formatCode($title, $body, $row, $col) {
    // echo $body;
    ob_start();

    ?>

    <link href="styles/prettify.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="js/prettify.js"></script>
    <link href="styles/sunburst.css" type="text/css" rel="stylesheet" />
    <!-- <link href="styles/son-of-obsidian.css" type="text/css" rel="stylesheet" /> -->

    <div style="text-align:center">
        <!-- <pre class="prettyprint" style="width:<?php echo $col?>px; text-align:left"> -->
        <pre class="prettyprint" style="text-align:left">
            <?php echo $body;?>
        </pre>
    </div>

    <?php
    $r = ob_get_contents();
    ob_end_clean();
    return $r;
}
?>
