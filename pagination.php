<?php
if ($total_pages > 1) {
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        echo "<a href='inventory.php?page=$i&search=$search' class='$active'>$i</a>";
    }
    echo "</div>";
}
?>