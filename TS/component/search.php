    <!-- search bar -->
    <form id="SearchBar" class="pad-s flex gap5" action="./<?php echo isset($page) ? $page : 'software';?>.php">
        <?php if(isset($subpage) && isset($paramsubpage)){ ?><input type="text" name="<?php echo $paramsubpage;?>" value="<?php echo $subpage;?>" hidden tabindex="99"><?php };?>
        <input type="text" name="item" placeholder="search stuff..." id="searchbox" class="inputext" tabindex="1">
        <button type="submit" name="onsearch" class="searchbtn" tabindex="2">Search</button>
        <a href="<?php echo isset($page) ? $page : 'software';?>.php<?php if(isset($subpage) && isset($paramsubpage)){echo '?' . $paramsubpage . '=' . $subpage;};?>" class="searchbtn" tabindex="3">Clear</a>
    </form>