# Server Client vs Zyte API

The [GuzzleHttp Client](https://docs.guzzlephp.org/en/stable/) is set as the default HTTP client in our application, providing a straightforward and powerful way to make HTTP requests. However, we understand the importance of flexibility and the need to adapt to different project requirements or personal preferences. Therefore, we've designed our system to allow for easy swapping of the HTTP client.

If you have a preference for another HTTP client, such as the [Symfony Http Client](https://symfony.com/doc/current/http\_client.html), you can effortlessly integrate it into our system. The Symfony Http Client is known for its powerful features and adaptability, making it a suitable alternative for projects requiring advanced HTTP client functionalities.

The [Zyte API ](https://www.zyte.com/zyte-api/)provides a robust web scraping tool that can be easily integrated into existing systems. This is particularly beneficial for overcoming common web scraping hurdles such as JavaScript or cookie dependency, or IP address-based blocking due to excessive requests. Zyte API Integration also provides extensive support for targeted API integrations to meet specific user needs.

<table><thead><tr><th width="159"></th><th width="277">Server Client</th><th>Zyte API</th></tr></thead><tbody><tr><td>Cost</td><td>Cheaper for smaller application</td><td>Less than a cent per Request: <a href="https://www.zyte.com/pricing/">Price site</a></td></tr><tr><td>Make a lot of requests</td><td>You will be get banned fast</td><td>No problems</td></tr><tr><td>Crawl sites with bot protection</td><td>Not possible</td><td>Possible</td></tr></tbody></table>

Utilizing the `fallback` feature in the `Crawler` allows you to leverage dual benefits!
