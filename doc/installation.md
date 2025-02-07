# Installation
1. Add package to your project via composer:
    ```bash
    composer require bitbag/user-com-plugin
    ```
   >If `bitbag/user-com-plugin` is not found by composer, try adding repository to your `composer.json` file:
   > ```json
   > "repositories": [
   >    {
   >       "type": "vcs",
   >      "url": "https://github.com/BitBagCommerce/SyliusUserComPlugin.git"
   >    }
   > ]
   > ```
2. Add plugin dependencies to config/bundles.php file:
    ```php
    return [
        ...
        BitBag\SyliusUserComPlugin\BitBagSyliusUserComPlugin::class => ['all' => true],
        Spinbits\SyliusGoogleAnalytics4Plugin\SpinbitsSyliusGoogleAnalytics4Plugin::class => ['all' => true],
        ...
    ];
    ```
3. Import required config in your `config/packages/_sylius.yaml` file:
    ```yaml
    imports:
        ...
        - { resource: "@BitBagSyliusUserComPlugin/config/config.yml" }
        ...
    ```
4. Extend `Channel` entity `UserComApiAwareTrait` and implement `UserComApiAwareInterface` 
    ```php
    class Channel extends BaseChannel implements ChannelInterface
    {
        use UserComApiAwareTrait;
    }
    ```
    
    ```php
    interface ChannelInterface extends BaseChannelInterface, UserComApiAwareInterface
    {
    }
    ```
    >`UserComApiAwareTrait` contains mapping for annotations and for attributes which are required by UserCom integration.
    > If you're using xml mapping, you should add mapping for those properties in your `Channel.orm.xml` file.

5. Add required environment variables to your `.env` file:
    ```dotenv
    USER_COM_FRONTEND_API_KEY=""
    TAG_MANAGER_ID=""
    ```

6. *Optional* - take advantage of channel based configuration of Google Tag Manager ID:
    ```yaml
    spinbits_sylius_google_analytics4:
        id: "%env(TAG_MANAGER_ID)%"
        channels_ids:
            FASHION_WEB: "G-DF1P3RRJ8S"
            OTHER_CHANNEL: "G-WX1RJ8SP3R"
    ```
