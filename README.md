## Description
This free plugin enhances performance profiling in Shopware 6 by adding targeted profiler calls at several key points, making monitoring with Tideways or other integrations significantly easier and more effective.

The plugin enables a more precise analysis of performance bottlenecks by capturing and visualizing relevant data in the following areas:

- Entity Loaded Events: Shows when and how long specific entities are loaded, aiding in identifying bottlenecks.
- Twig Template Includes: Measures the rendering times of individual Twig templates, helping to quickly identify slow-loading templates.
- Flow Execution Times: Monitors the duration of flow processes, enabling optimized process analysis.
- Mail Creation: Provides insights into the performance of email generation, allowing better control over loading times in the communication process.

By integrating these profiler calls, it becomes easier to make targeted optimizations and closely monitor the performance of Shopware instances.

<img width="992" alt="image" src="https://github.com/user-attachments/assets/53689c8e-a150-43ac-b7ae-127e8052279c">

<img width="981" alt="image" src="https://github.com/user-attachments/assets/01453331-f9e3-47a9-8096-42bf47638526">

## FAQ

Q: *I see no difference in Tideways*

A: You have to activate the `Tideways` profiler integration in your `shopware.yaml`

```yaml
shopware:
    profiler:
        integrations:
            - Tideways
```

See: https://developer.shopware.com/docs/resources/references/adr/2022-03-25-profiler-integrations.html
