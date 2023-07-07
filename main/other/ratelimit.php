<?php
    session_start();

    $badUserAgents = array(
        'cURL',
        'Python-urllib',
        'python-requests',
        'Scrapy',
        'node-fetch',
        'Apache'
    );

    if (isset($_SESSION['rate_limit_triggered']) && $_SESSION['rate_limit_triggered']) {
        header("Location: /blocked.php");
        exit();
    }

    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $isBadUserAgent = false;
    foreach ($badUserAgents as $badAgent) {
        if (strpos($userAgent, $badAgent) !== false) {
            $isBadUserAgent = true;
            break;
        }
    }

    $_SESSION['request_count'] = isset($_SESSION['request_count']) ? $_SESSION['request_count'] + 1 : 1;

    if (getRequestCountInTimeframe(20) >= rand(10, 20)
        || getRequestCountInTimeframe(20) >= rand(30, 40)
        || getRequestCountInTimeframe(240) >= rand(300, 400)
        || $isBadUserAgent
    ) {
        $_SESSION['rate_limit_triggered'] = true;
        header("Location: /blocked.php");
        exit();
    }

    if (isset($_SESSION['rate_limit_time']) && time() > $_SESSION['rate_limit_time']) {
        unset($_SESSION['rate_limit_triggered']);
        unset($_SESSION['rate_limit_time']);
        unset($_SESSION['request_count']);
    }

    function getRequestCountInTimeframe($seconds) {
        $count = 0;
        $now = time();
        
        if (isset($_SESSION['timestamps'])) {
            foreach ($_SESSION['timestamps'] as $timestamp) {
                if ($now - $timestamp <= $seconds) {
                    $count++;
                }
            }
        }

        $_SESSION['timestamps'][] = $now;

        return $count;
    }

?>