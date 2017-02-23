This bundle will add a Monolog processor that adds the client IP, user-agent, kernel environment and application name to the extra data of each log line.
It also provides an event listener that listens to all events, logging any that implement the `LoggableEvent` interface.

## Installation
`composer require vivait/logging-bundle`

Include the bundle in your `AppKernel`:
```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Vivait\LoggingBundle\VivaitLoggingBundle(),
        );

        // ...
    }
}
```
Add the following configuration to your `config.yml`:
```yaml
vivait_logging:
    application_name: MyAppName
    # Alternatively...
    application_name: '%my_name_parameter%'
```

## Usage
To use the loggable event listener, simply fire any event that implements the `LoggableEvent` interface, such as the `GenericLogEvent` that is part of this bundle.
The extra data should automatically be included at the end of each line in your logs but the provided formatter can also be used:

```yaml
# config.yml
monolog:
    handlers:
        main:
            type: stream
            path: '%kernel.logs_dir%/%kernel.environment%.log'
            level: debug
            formatter: vivait.logging_bundle.log_formatter
```

which will display logs in the following format:
```
[%%datetime%%] [%%extra.App%%] [%%extra.Environment%%] %%channel%%.%%level_name%%: %%message%% [%%extra.IP%%] [%%extra.UA%%] %%context%% %%extra%%
```
