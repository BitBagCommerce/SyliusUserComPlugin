# Installation
1. Add package to your project via composer:
    ```bash
    composer require bitbag/user-com-plugin
    ```
2. Add required environment variables to your `.env` file:
    ```dotenv
        USER_COM_FRONTEND_API_KEY=""
        TAG_MANAGER_ID=""
        USER_COM_ENCRYPTION_KEY=your-32-character-long-key
        USER_COM_ENCRYPTION_IV=your-16-character-long-iv
    ```
3. Add plugin dependencies to `config/bundles.php` file. Make sure that none of the bundles are duplicated.
    ```php
    return [
        ...
            BitBag\SyliusUserComPlugin\BitBagSyliusUserComPlugin::class => ['all' => true],
            Spinbits\SyliusGoogleAnalytics4Plugin\SpinbitsSyliusGoogleAnalytics4Plugin::class => ['all' => true],
            League\FlysystemBundle\FlysystemBundle::class => ['all' => true],
            Setono\SyliusFeedPlugin\SetonoSyliusFeedPlugin::class => ['all' => true],
            Setono\DoctrineORMBatcherBundle\SetonoDoctrineORMBatcherBundle::class => ['all' => true],
           //Sylius grid bundle should be under Setono\SyliusFeedPlugin\SetonoSyliusFeedPlugin
            Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
        ...
    ];
    ```
4. Import required config in your `config/packages/_sylius.yaml` file:
    ```yaml
    imports:
        ...
        - { resource: "@BitBagSyliusUserComPlugin/config/config.yml" }
        ...
    ```

4. Import routes in your `config/routes.yaml` file:
    ```yaml
        bitbag_sylius_user_com_plugin:
            resource: "@BitBagSyliusUserComPlugin/config/routes.yaml"
    ```

5. Extend `Channel` entity `UserComApiAwareTrait` and implement `UserComApiAwareInterface` 
   ```php
    use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareTrait;
    use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
   ... 
    class Channel extends BaseChannel implements UserComApiAwareInterface
    {
        use UserComApiAwareTrait;
    }
    ```
    
    >`UserComApiAwareTrait` contains mapping for annotations and for attributes which are required by UserCom integration.
    > If you're using xml mapping, you should add mapping for those properties in your `Channel.orm.xml` file.

6. Take advantage of channel based configuration of GoogleAnalyticsPlugin by adding :
    ```yaml
    spinbits_sylius_google_analytics4:
        id: "%env(TAG_MANAGER_ID)%"
    ```
