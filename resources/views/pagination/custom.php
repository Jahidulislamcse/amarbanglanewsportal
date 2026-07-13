<?php if ($paginator->hasPages()): ?>
    <ul class="pager">
        <!-- Previous Button -->
        <?php if ($paginator->onFirstPage()): ?>
            <li class="disabled">
                <span><i class="fa fa-backward" aria-hidden="true"></i></span>
            </li>
        <?php else: ?>
            <li>
                <a href="<?php echo $paginator->previousPageUrl(); ?>" rel="prev" title="Previous">
                    <i class="fa fa-backward" aria-hidden="true"></i>
                </a>
            </li>
        <?php endif; ?>

        <!-- Pagination Numbers -->
        <?php foreach ($elements as $element): ?>
            <!-- Dots ("...") -->
            <?php if (is_string($element)): ?>
                <li class="disabled"><span><?php echo $element; ?></span></li>
            <?php endif; ?>

            <!-- Array of Links -->
            <?php if (is_array($element)): ?>
                <?php foreach ($element as $page => $url): ?>
                    <?php if ($page == $paginator->currentPage()): ?>
                        <li class="active">
                            <span><?php echo str_pad($page, 2, '0', STR_PAD_LEFT); ?></span>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?php echo $url; ?>"><?php echo str_pad($page, 2, '0', STR_PAD_LEFT); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Next Button -->
        <?php if ($paginator->hasMorePages()): ?>
            <li>
                <a href="<?php echo $paginator->nextPageUrl(); ?>" rel="next" title="Next">
                    <i class="fa fa-forward" aria-hidden="true"></i>
                </a>
            </li>
            <li class="next">
                <a href="<?php echo $paginator->url($paginator->lastPage()); ?>" title="Last">
                    <i class="fa fa-fast-forward" aria-hidden="true"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="disabled">
                <span><i class="fa fa-forward" aria-hidden="true"></i></span>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>
