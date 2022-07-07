<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if ( ! class_exists( 'FilterTabsProducts_Widget' ) ) {
    class FilterTabsProducts_Widget extends Widget {

        /**
         * {@inheritdoc}
         */
        protected function widgetName() {
            return __('W - Filter Tabs Products', 'hd' );
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription() {
            return __( 'Filter Tabs Products by Categories + Custom Fields', 'hd' );
        }

        /**
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) {

            /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
            $title = $instance['title'] ?: '';

            // ACF attributes
            $ACF = $this->acfFields( 'widget_' . $args['widget_id'] );
            if ( is_null( $ACF ) ) {
                wp_die( __( 'Required: "Advanced Custom Fields" plugin', 'hd' ) );
            }

            // class
            $_class = $this->id;
            if ( $ACF->css_class ) {
                $_class = $_class . ' ' . $ACF->css_class;
            }

            ?>
            <section class="section filter-products tab-filter-products <?= $_class ?>">
                <?php if ( ! $ACF->full_width ) echo '<div class="grid-container width-extra">'; ?>
                <?php if ($title) : ?>
                <h2 class="heading-title"><?php echo $title; ?></h2>
                <?php endif; ?>
                <?php

                // loop
                if ( $ACF->product_cat ) :
                    $all_id = $this->id;

                ?>
                <div class="w-filter-tabs">
                    <div class="filter-tabs">
                        <ul>
                            <li class="tabs-inner">
                                <a data-tab="all" aria-label="<?php echo esc_attr__('All', 'hd'); ?>" class="tab-title" href="#<?=$all_id?>"><?php echo __('All', 'hd'); ?></a>
                            </li>
                            <?php
                            foreach ( $ACF->product_cat as $i => $term_id ) :
                                $term = get_term( $term_id );
                            ?>
                            <li class="tabs-inner">
                                <a data-tab="<?=$term_id?>" aria-label="<?php echo esc_attr($term->name); ?>" class="tab-title" href="#<?php echo $all_id . '_' . $term_id; ?>"><?php echo $term->name; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="filter-tabs-content">
                        <div class="tabs-panel" id="<?=$all_id?>" aria-labelledby="<?php echo esc_attr__('All', 'hd'); ?>">
                            <?php
                            $r = query_by_terms(null, 'product_cat', 'product', $ACF->include_children, $ACF->product_number);
                            if ($r) :
                                echo '<div class="grid-x grid-tab-products grid-products">';
                                $i = 0;

                                // Load slides loop
                                while ($r->have_posts() && $i < $ACF->product_number) : $r->the_post();
                                    global $product;
                                    if (empty($product) || FALSE === wc_get_loop_product_visibility($product->get_id()) || !$product->is_visible()) {
                                        continue;
                                    }

                                    echo '<div class="cell">';
                                    wc_get_template_part('content', 'product');
                                    echo '</div>';
                                    ++$i;

                                endwhile;
                                wp_reset_postdata();
                                echo '</div>';
                                if ($ACF->url) :
                            ?>
                            <a href="<?= get_permalink( wc_get_page_id( 'shop' ) ) ?>" class="button view-more" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                            <?php endif; endif; ?>
                        </div>
                        <?php
                        foreach ( $ACF->product_cat as $i => $term_id ) :
                            $term = get_term( $term_id );
                            $r = query_by_terms($term_id, 'product_cat', 'product', $ACF->include_children, $ACF->product_number);
                        ?>
                        <div class="tabs-panel" id="<?php echo $all_id . '_' . $term_id; ?>" aria-labelledby="<?php echo esc_attr($term->name); ?>">
                            <?php
                            if ($r) : echo '<div class="grid-x grid-tab-products grid-products">';
                                $i = 0;

                                // Load slides loop
                                while ($r->have_posts() && $i < $ACF->product_number) : $r->the_post();
                                    global $product;
                                    if (empty($product) || FALSE === wc_get_loop_product_visibility($product->get_id()) || !$product->is_visible()) {
                                        continue;
                                    }

                                    echo '<div class="cell">';
                                    wc_get_template_part('content', 'product');
                                    echo '</div>';
                                    ++$i;

                                endwhile;
                                wp_reset_postdata();
                                echo '</div>';
                                if ($ACF->url) :
                                ?>
                                <a href="<?= get_term_link($term, 'product_cat') ?>" class="view-more button" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                            <?php
                                endif;
                            endif;
                            ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!$ACF->full_width) echo '</div>'; ?>
            </section>
            <?php
        }

        /**
         * Outputs the settings update form.
         *
         * @param array $instance Current settings.
         *
         * @return void
         * @since 2.8.0
         *
         */
        public function form( $instance ) {
            $instance = wp_parse_args(
                Cast::toArray($instance),
                [
                    'title' => '',
                ]
            );
            $this->widgetArgs = $instance;
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'hd' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
            </p>
            <?php
        }

        /**
         * @param array $newInstance
         * @param array $oldInstance
         *
         * @return array
         */
        public function update($newInstance, $oldInstance)
        {
            $newInstance['title'] = sanitize_text_field($newInstance['title']);
            return parent::update($newInstance, $oldInstance);
        }
    }
}