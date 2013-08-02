<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php tr8n_begin_block_with_options(array("source" => "/docs/navigation")) ?>

            <div class="sidebar-nav-fixed">
                <div class="well">
                    <ul class="nav nav-list">
                        <li class="nav-header">Sections</li>
                        <?php list_link_tag("Introduction", "docs/introduction.php") ?>
                        <?php list_link_tag("Tr8n Syntax", "docs/tml.php") ?>
                        <?php list_link_tag("Rules", "docs/rules.php") ?>
                    </ul>
                </div><!--/.well -->
            </div> <!--/sidebar-nav-fixed -->

            <?php tr8n_finish_block_with_options() ?>
        </div><!--/span-->

        <div class="span9 span-fixed-sidebar">