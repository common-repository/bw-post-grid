<?php  
// BW post grid Shortcodes 

$col_no=$count=$col_width=$post_count='';
global $image_size;
function bw_post_grid_shortcode( $atts ) {
    extract( shortcode_atts( array (
         'post_type'  => 'post',
         'filter_thumbnail' => 0,
         'taxonomy_type'    => '',
         'cat_exclude'      => '', // actually include or exclude both
         'terms'            => '', 
         'display_type'     => '1',   
         'posts'            => -1,
         'posts_per_row'    =>  2,         
         'image_style'      => '1',
         'orderby'          => 'date',
         'order'            => 'DESC', 
         'offset'           =>  0,  
         'sticky_ignore'    =>  0,
         'display_type'     => 'grid',
         'pagination_yes'   =>  1,
         'show_date'        => 1,
         'show_excerpt'     =>  1,
         'excerpt_length'   => 20,
		 'image_size'       =>  ''
    ), $atts ));


  global $col_no,$count,$col_width;
  
  $count = 0;         
  if ($posts_per_row==1)  { $col_width = 12; $col_no = 1; }
  else if ($posts_per_row==2){ $col_width = 6; $col_no = 2; }
  else if ($posts_per_row==3){ $col_width = 4; $col_no = 3; }
  else if ($posts_per_row==4){ $col_width = 3; $col_no = 4; }
  else if ($posts_per_row==6){ $col_width = 2; $col_no = 6; }
  else{ $col_width = 12; $col_no = 1; }
   
    if( $display_type == '1' ){
      $display_type = 'grid';
    }
    elseif( $display_type == '2'){
      $display_type = 'list';
    }
    elseif( $display_type == '3'){
      $display_type = 'first-post-grid';
    }
    elseif( $display_type == '4'){
      $display_type = 'first-post-list';
    }
    elseif( $display_type == '5'){
      $display_type = 'minimal';
    }
    
    if( !empty( $image_style ) ){
      if( $image_style == 1){
        $image_style = '';
      }
      elseif( $image_style == 2){
        $image_style = 'top-left';
      }
      else{
        $image_style = 'top-right';
      }
    }
    else{
      $image_style = '';
    }         
  if( !empty( $taxonomy_type ) ){
    $tax_query = array(                        
                    array(
                            'taxonomy' => $taxonomy_type,
                            'field'    => 'term_id',
                            'terms'    => explode( ',', $terms ),
                          ),
                    );
  }
  else{
    $tax_query = '';
  }


  $grid_query= null;

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} elseif ( get_query_var('page') ) { // if is static front page
    $paged = get_query_var('page');
} else {
    $paged = 1;
}


    $args = array(
       'post_type'      => $post_type,
       'cat'            => $cat_exclude,        // actually include or exclude both  
       'post_status'    => 'publish',
       'posts_per_page' => $posts, 
       'paged'          => $paged,   
       'tax_query'      => $tax_query,
       'orderby'        => $orderby,
       'order'          => $order,   //ASC / DESC
       'ignore_sticky_posts' => $sticky_ignore,
       'bw_set_offset' => $offset,
  );

$grid_query = new WP_Query( $args );

global $post_count;
$post_count = $posts;
$total = $grid_query->found_posts;
ob_start();  ?>

<div class="content-area-bw bw-post-grid">
  <div class="site-main-bw <?php echo esc_html( $display_type . ' '. $image_style); ?>" >              
      <?php
      if ( $grid_query->have_posts() ) : 

            /* Start the Loop */
        while ( $grid_query->have_posts() ) : $grid_query->the_post();  // Start of posts loop found posts
          
          if($count==0){
		  ?>
		  
		  <div class="row firstpost">
		  <div class="col-md-12">
    		  <div class="bw-featured-image"><?php the_post_thumbnail(); ?>
    		  <h2 class="entry-title" ><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    		  </div>
		  </div>
		  </div>
		  <?php
          }else{
            
            if(($count % 2)){echo '<div class="row otherpost">';} ?>
            
               <div class="col-md-6">
               <div class="bw-featured-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
               <?php if($show_date==1) : ?>
               <div class="bw-post-grid-date"><em><i><?php echo get_the_date('F j, Y',get_the_ID()); ?></i></em></div>
               <?php endif; ?>
		       <h2 class="entry-title" >
		           <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		       </h2>
		       <?php if($show_excerpt==1) : ?>
    		       <div class="bw-post-grid-excerpt" style="margin-bottom:15px;"><?php 
    		        $content = get_the_content();
                    echo wp_trim_words( $content , $excerpt_length ); ?>
                    </div>
		       <?php endif; ?>
               </div>
               </div>
            
            <?php
           if(($count % 2)){echo '';}else{echo '</div>';}
              
            }
            $count++;
        endwhile; // End of posts loop found posts
        if(($total % 2)){}else{echo '</div>';} // fix closing div for row class if last row has 1 post

        if($pagination_yes==1) :  //Start of pagination condition 
            global $wp_query;
            $big = 999999999; // need an unlikely integer
            $totalpages = $grid_query->max_num_pages;
            $current = max(1,$paged );
            $paginate_args = array(
                                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))) ,
                                'format' => '?paged=%#%',
                                'current' => $current,
                                'total' => $totalpages,
                                'show_all' => False,
                                'end_size' => 1,
                                'mid_size' => 3,
                                'prev_next' => True,
                                'prev_text' => esc_html__('« Previous') ,
                                'next_text' => esc_html__('Next »') ,
                                'type' => 'plain',
                                'add_args' => False,
                              );

            $pagination = paginate_links($paginate_args); ?>
            <div class="col-md-12">
              <nav class='pagination wp-caption bw-grid-nav'> 
               <?php echo $pagination; ?>
              </nav>
            </div>
        <?php endif; //end of pagination condition ?>


      <?php else :   //if no posts found

          //$templates->get_template_part( 'content', 'none' );
		  echo 'No posts';

      endif; //end of post loop ?>  

 
  </div><!-- #primary -->
</div>
<div class="clearfix"></div>
<?php
wp_reset_postdata();
return ob_get_clean();
}

add_shortcode('bw_post_grid_shortcode', 'bw_post_grid_shortcode');

