<?php

// Set timeout
set_time_limit(0);

// Inculde thư viện PHPCrawl
include("libs/PHPCrawler.class.php");
include("simple_html_dom.php");


// Extend the class PHPCrawler and cài đè phương thức handleDocumentInfo()
class MyCrawler extends PHPCrawler
{
    // Các bạn cài đè phương thức handleDocumentInfo() để xử lý tất cả các thông tin thu tập được.
    function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo)
    {

        // Lấy toàn bộ url của website
        echo "Page requested: ".$DocInfo->url."\n";

        //if(strpos($contents, $DocInfo->url) === false && strpos($contents, $DocInfo->url) === false){
        if (strpos($DocInfo->url, "/tin-tuc/giao-duc/") !== false && strpos($DocInfo->url, "?") === false) {
            $html = file_get_html($DocInfo->url);
            if(is_object($html)){
                $type = $html->find("meta[name='tt_page_type']", 0)->content;
                $id = $html->find("meta[name='tt_article_id']", 0)->content;
                if($type === "article"){
                    $t = $html->find('.block_ads_connect', 0)->plaintext;
                    //$t = str_replace("'", '"', $t);
                    $t = addslashes($t);
                    //echo trim($t)."\n";
                    $t = preg_replace('/[ ]{2,}|[\t]/', '', trim($t))."\n";

                    $file = 'logs_id_giaoduc';
                    $contents = file_get_contents($file);
                    if(strpos($contents, $id) === false){
                        $giaoduc = file_put_contents('logs_giaoduc', $t.PHP_EOL , FILE_APPEND | LOCK_EX);
                        $logs_id = file_put_contents('logs_id_giaoduc', $DocInfo->url."\n".$id."\n".PHP_EOL , FILE_APPEND | LOCK_EX);
                    }
                    $html->clear();
                    unset($html);
                }
            }
        }
        if (strpos($DocInfo->url, "/tin-tuc/khoa-hoc/") !== false && strpos($DocInfo->url, "?") === false) {
            $html = file_get_html($DocInfo->url);
            if(is_object($html)){
                $type = $html->find("meta[name='tt_page_type']", 0)->content;
                $id = $html->find("meta[name='tt_article_id']", 0)->content;
                if($type === "article"){
                    $t = $html->find('.block_ads_connect', 0)->plaintext;
                    //$t = str_replace("'", '"', $t);
                    $t = addslashes($t);
                    //echo trim($t)."\n";
                    $t = preg_replace('/[ ]{2,}|[\t]/', '', trim($t))."\n";

                    $file = 'logs_id_khoahoc';
                    $contents = file_get_contents($file);
                    if(strpos($contents, $id) === false){
                        $khoahoc = file_put_contents('logs_khoahoc', $t.PHP_EOL , FILE_APPEND | LOCK_EX);
                        $logs_id = file_put_contents('logs_id_khoahoc', $DocInfo->url."\n".$id."\n".PHP_EOL , FILE_APPEND | LOCK_EX);
                    }
                    $html->clear();
                    unset($html);
                }
            }
        }
        if (strpos($DocInfo->url, "/tin-tuc/phap-luat/") !== false && strpos($DocInfo->url, "?") === false) {
            $html = file_get_html($DocInfo->url);
            if(is_object($html)){
                $type = $html->find("meta[name='tt_page_type']", 0)->content;
                $id = $html->find("meta[name='tt_article_id']", 0)->content;
                if($type === "article"){
                    $t = $html->find('.block_ads_connect', 0)->plaintext;
                    //$t = str_replace("'", '"', $t);
                    $t = addslashes($t);
                    //echo trim($t)."\n";
                    $t = preg_replace('/[ ]{2,}|[\t]/', '', trim($t))."\n";

                    $file = 'logs_id_phapluat';
                    $contents = file_get_contents($file);
                    if(strpos($contents, $id) === false){
                        $phapluat = file_put_contents('logs_phapluat', $t.PHP_EOL , FILE_APPEND | LOCK_EX);
                        $logs_id = file_put_contents('logs_id_phapluat', $DocInfo->url."\n".$id."\n".PHP_EOL , FILE_APPEND | LOCK_EX);
                    }
                    $html->clear();
                    unset($html);
                }
            }
        }
        //}
        flush();
    }
}

// Tạo đối tượng crawler và bắt đầu tiến trình thu thập dữ liệu

$crawler = new MyCrawler();

// set URL mà ta muốn crawler
$crawler->setURL("https://vnexpress.net/");

// Chỉ lấy các file mà nội dung là "text/html"
$crawler->addContentTypeReceiveRule("#text/html#");

// Một bộ lọc cho phép ta không lấy các link ảnh, css hoặc javascript
$crawler->addURLFilterRule("#(jpg|gif|png|pdf|jpeg|svg|css|js)$# i");

// Cai dat khoi dong lai
$crawler->enableResumption();
if (!file_exists("/tmp/example.tmp"))
{
    $crawler_ID = $crawler->getCrawlerId();
    file_put_contents("/tmp/example.tmp", $crawler_ID);
}
else
{
    $crawler_ID = file_get_contents("/tmp/example.tmp");
    $crawler->resume($crawler_ID);
}

// Trong quá trình crawler, lưu trữ và gửi cookie giống như ta vào bằng trinh duyệt
$crawler->enableCookieHandling(true);

// Thiết lập dung lượng(bytes) thu thập được trong quá trình crawler
//$crawler->setTrafficLimit(1000 * 1024);


// Nào, chạy thôi, hehe, :))
$crawler->go();
//$crawler->goMultiProcessed(5);

// Sau khi quá trình crawler kết thúc, ghi lại báo cáo!!

//$report = $crawler->getProcessReport();

?>
