<?php get_header(); ?>
<div id="page-content">
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
  <h2>
    <?php the_title(); ?>
  </h2>




  <style>
        table, td, th {
        border: 1px solid #ddd;
        text-align: left;
        }

        table {
        border-color: black;
        width: 100%;
        border: 1px solid #ddd;
        background-color: #f2f2f2;
        }

        th, td {
        padding: 15px;
        }
  </style>



<table>

    <thead>
        <tr>
            <th>Time</th>
            <th>Date</th>
            <th>Reference</th>
            <th>Service</th>
            <th>Credits</th>
            <th>Charge</th>
            <th>Profit</th>
        </tr>
    </thead>

    <tbody style="background-color: #fff;">
          <tr>
            <td>10:12 am</td>
            <td>05/11/2017</td>
            <td>SMS Credits</td>
            <td>SMS</td>
            <td>136</td>
            <td>233</td>
            <td>111</td>
          </tr>
           <tr>
            <td>02:12 am</td>
            <td>05/11/2017</td>
            <td>SMS Credits</td>
            <td>SMS</td>
            <td>136</td>
            <td>233</td>
            <td>111</td>
          </tr>

           <tr>
            <td>03:12 am</td>
            <td>05/11/2017</td>
            <td>SMS Voice</td>
            <td>SMS</td>
            <td>136</td>
            <td>233</td>
            <td>111</td>
          </tr>

          <tr>
            <td>112:12 am</td>
            <td>05/11/2017</td>
            <td>SMS Fax</td>
            <td>SMS</td>
            <td>136</td>
            <td>233</td>
            <td>111</td>
          </tr>

  </tbody>
</table>





  <?php

    // $id=$post->ID;
    // $post = get_post($id);
    // $content = apply_filters('the_content', $post->post_content);

  ?>
  <?php the_content(); ?>

  <div style="clear:both"></div>
  <?php endwhile; ?>
  <?php else : ?>
  <h2 class="center">Not Found</h2>
  <p class="center">Sorry, but you are looking for something that isn't here.</p>
  <?php get_search_form(); ?>
  <?php endif; ?>

</div>
<?php get_footer(); ?>
