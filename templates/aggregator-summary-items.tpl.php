<?php
// $Id: aggregator-summary-items.tpl.php,v 1.1.2.1 2010/06/24 23:16:57 grendzy Exp $

/**
 * @file
 * Default theme implementation to present feeds as list items.
 *
 * Each iteration generates a single feed source or category.
 *
 * Available variables:
 * - $title: Title of the feed or category.
 * - $summary_list: Unordered list of linked feed items generated through
 *   theme_item_list().
 * - $source_url: URL to the local source or category.
 *
 * @see template_preprocess()
 * @see template_preprocess_aggregator_summary-items()
 */
?>
<section id="feed-<?php print $title; ?>" class="feed">
<h3><?php print $title; ?></h3>
<?php print $summary_list; ?>
<div class="links">
<a href="<?php print $source_url; ?>"><?php print t('More'); ?></a>
</div>
</section> <!-- /.feed -->
                                   