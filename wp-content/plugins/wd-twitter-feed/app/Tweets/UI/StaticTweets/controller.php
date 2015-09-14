<?php
/**
 * @package    twitterfeed
 * @date       Thu Aug 20 2015 10:24:31
 * @version    2.1.0
 * @author     Askupa Software <contact@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2015 Askupa Software
 */

namespace TwitterFeed\Tweets\UI;

/**
 * Implements a static tweet list controller.
 */
class StaticTweets extends \TwitterFeed\Tweets\AbstractTweet 
{
    public function get_defaults()
    {
        return array(
            'skin'      => 'simplistic',
            'direction' => 'ltr',
            'show'      => array()
        );
    }
}
