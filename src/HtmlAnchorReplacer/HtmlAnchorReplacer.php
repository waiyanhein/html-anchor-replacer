<?php

namespace HtmlAnchorReplacer;

class HtmlAnchorReplacer
{
    public static function replace($text)
    {
        $shortest_url_len = 7;
        if (strlen($text) >= $shortest_url_len) {
            $words = explode(" ", $text);
            if (count($words) > 0) {
                $crawled_words = array();
                foreach ($words as $word) {
                    $check_by_http = false;
                    if (!empty($word) and strlen($word) > $shortest_url_len and (strpos(strtolower($word), "https://") > -1 or strpos(strtolower($word), "http://") > -1 or strpos(strtolower($word), "www") > -1)) {
                        $first_position = 0;
                        if (strpos(strtolower($word), "www") > -1) {
                            $first_position = strpos(strtolower($word), "www");
                        } else {
                            if (strpos(strtolower($word), "https://")) {
                                $first_position = strpos(strtolower($word), "https://");
                            } else {
                                $first_position = strpos(strtolower($word), "http://");
                            }
                            $check_by_http = true;
                        }
                        $last_position = 0;
                        for ($i = strlen($word) - 1; $i > -1; $i--) {
                            if (ctype_alnum($word[$i]) and $last_position < 1) {
                                $last_position = $i;
                            }
                        }
                        if ($last_position > 0 and $first_position < $last_position) {
                            $http_prefixed = false;
                            if ($check_by_http) {
                                $http_prefixed = true;
                            } else {
                                if ($first_position > 7) {
                                    $https_prefix_first_position = $first_position - 8;
                                    $https = substr($word, $https_prefix_first_position, 8);
                                    if (strtolower($https) == "https://") {
                                        $first_position = $https_prefix_first_position;
                                        $http_prefixed = true;
                                    }
                                }
                                if (!$http_prefixed and $first_position >= 6) {
                                    $http_prefix_first_position = $first_position - 7;
                                    $http = substr($word, $http_prefix_first_position, 7);
                                    if (strtolower($http) == "http://") {
                                        $first_position = $http_prefix_first_position;
                                        $http_prefixed = true;
                                    }
                                }
                            }
                            if (!$http_prefixed) {
                                $link_text = substr($word, $first_position, $last_position + 1);
                                $link = "<a target='_blank' href='http://" . $link_text . "'>" . $link_text . "</a>";
                                $word = substr_replace($word, $link, $first_position, $last_position + 1); //may be plus 1 here
                            } else {
                                $link_text = substr($word, $first_position, $last_position + 1);
                                $link = "<a target='_blank' href='" . $link_text . "'>" . $link_text . "</a>";
                                $word = substr_replace($word, $link, $first_position, $last_position + 1); //may be plus 1 here
                            }
                            $crawled_words[] = $word;
                        } else {
                            $crawled_words[] = $word;
                        }
                    } else {
                        $crawled_words[] = $word;
                    }
                }
                return implode(" ", $crawled_words);
            } else {
                return $text;
            }
        } else {
            return $text;
        }
    }
}
