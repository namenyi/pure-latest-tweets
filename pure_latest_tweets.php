<?php

/**
 * Adds Pure Latest Tweets widget.
 * Uses Twitter-Post-Fetcher ( @link https://github.com/jasonmayes/Twitter-Post-Fetcher )
 */
class Pure_latest_tweets extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'Pure_latest_tweets', // Base ID
            'Pure Latest Tweets', // Name
            array('description' => 'Loads the latest tweets for the specified Twitter ID. No iFrame, no styles, just the tweets.') // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $description = $instance['description'];
        $twitterWidgetId = (isset($instance['twitter_widget_id'])) ? $instance['twitter_widget_id'] : "";
        $tweetCount = (!empty($instance['tweet_count'])) ? $instance['tweet_count'] : 3;
        $twitterScreenName = $instance['twitter_screen_name'];
        $twitterName = (!empty($instance['twitter_name'])) ? $instance['twitter_name'] : "Us";
        $currentId = rand(1, 100);

        echo $before_widget;
        ?>

        <div class="widget pure-latest-tweets">

    <?php if (!empty($title)): ?>
        <h2 class="widgettitle">
            <span><?php echo $title; ?></span>
        </h2>
    <?php endif; ?>

    <?php if (!empty($twitterScreenName)): ?>
        <div class="twitter-follow-wrapper">
            <a target="_blank" class="twitter-btn" title="Follow <?php echo $twitterName; ?> on Twitter!"
               href="https://twitter.com/intent/user?screen_name=<?php echo $twitterScreenName; ?>">
                <i></i>
                <span class="label">Follow</span>
            </a>
        </div>
    <?php endif; ?>

        <div class="clearfix"></div>

        <div class="pure-latest-tweets-wrapper">

            <?php if (!empty($description)): ?>
                <div class="pure-latest-tweets-heading">
                    <span class="pure-latest-tweets-description"><?php echo $description; ?></span>
                </div>
            <?php endif; ?>

            <div id="pure-latest-tweets-body-<?= $currentId ?>" class="pure-latest-tweets-body">

            </div>

        </div>

        <script type="text/javascript" src="<?= home_url(); ?>/wp-content/themes/iia/js/twitterFetcher_min.js"></script>

        <script type="text/javascript">
            var config = {
                "id": '<?= $twitterWidgetId ?>',
                "domId": 'pure-latest-tweets-body-<?= $currentId ?>',
                "maxTweets": <?= $tweetCount ?>,
                "showUser": true,
                "showTime": true,
                "dateFunction": dateFormatter,
                "enableLinks": true
            };
            function dateFormatter(date) {
                var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                return date.getDate() + " " + monthNames[date.getMonth()];
            }
            twitterFetcher.fetch(config);
        </script>

        <?php

        echo $after_widget . "</div>";

        /* Restore original Post Data */
        wp_reset_postdata(); ?>


    <?php
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {

        $title = (isset($instance['title'])) ? $instance['title'] : "";
        $description = (isset($instance['description'])) ? $instance['description'] : "";
        $twitter_widget_id = (isset($instance['twitter_widget_id'])) ? $instance['twitter_widget_id'] : "";
        $tweetCount = (isset($instance['tweet_count'])) ? $instance['tweet_count'] : "";
        $twitterScreenName = (isset($instance['twitter_screen_name'])) ? $instance['twitter_screen_name'] : "";
        $twitterName = (isset($instance['twitter_name'])) ? $instance['twitter_name'] : "";

        ?>
 
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
         
        <p>
            <label for="<?php echo $this->get_field_name('description'); ?>"><?php _e('Description:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo esc_attr($description); ?>" />
        </p>
         
        <p>
            <label for="<?php echo $this->get_field_name('twitter_widget_id'); ?>"><?php _e('Twitter Widget ID:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_widget_id'); ?>" name="<?php echo $this->get_field_name('twitter_widget_id'); ?>" type="text" value="<?php echo esc_attr($twitter_widget_id); ?>" />
        </p>
 
        <p>
            <label for="<?php echo $this->get_field_name('tweet_count'); ?>"><?php _e('Number of tweets to display:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('tweet_count'); ?>" name="<?php echo $this->get_field_name('tweet_count'); ?>" type="text" value="<?php echo esc_attr($tweetCount); ?>" />
        </p>
 
        <p>
            <label for="<?php echo $this->get_field_name('twitter_screen_name'); ?>"><?php _e('Twitter username (@something):'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_screen_name'); ?>" name="<?php echo $this->get_field_name('twitter_screen_name'); ?>" type="text" value="<?php echo esc_attr($twitterScreenName); ?>" />
        </p>
 
        <p>
            <label for="<?php echo $this->get_field_name('twitter_name'); ?>"><?php _e('Twitter name:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_name'); ?>" name="<?php echo $this->get_field_name('twitter_name'); ?>" type="text" value="<?php echo esc_attr($twitterName); ?>" />
        </p>
 
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? strip_tags($new_instance['description']) : '';
        $instance['twitter_widget_id'] = (!empty($new_instance['twitter_widget_id'])) ? strip_tags($new_instance['twitter_widget_id']) : '';
        $instance['tweet_count'] = (!empty($new_instance['tweet_count'])) ? strip_tags($new_instance['tweet_count']) : '';
        $instance['twitter_screen_name'] = (!empty($new_instance['twitter_screen_name'])) ? strip_tags($new_instance['twitter_screen_name']) : '';
        $instance['twitter_name'] = (!empty($new_instance['twitter_name'])) ? strip_tags($new_instance['twitter_name']) : '';

        return $instance;
    }

} // class Pure_latest_tweets


// register Pure_latest_tweets widget
add_action('widgets_init', function () {
    register_widget('Pure_latest_tweets');
});
