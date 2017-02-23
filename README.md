This bundle will add a Monolog processor that adds the client IP, user-agent, kernel environment and application name to the extra data of each log line.
It also provides an event listener that listens to all events, logging any that implement the `LoggableEvent` interface.

## Installation
1. Add the bundle to your `AppKernel.php`'s `registerBundles()` method
2. Set the parameter `vivait_logging_app_name` to the name of your application for logging purposes

## Usage
To use the loggable event listener, simply fire any event that implements the `LoggableEvent` listener, such as the `GenericLogEvent` that is part of this bundle.
The extra data should automatically be included at the end of each line in your logs but the provided formatter can also be used

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
