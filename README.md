Bundle automatically add mapping for Tag and Tagging entities. To disable put in config.yml

```yml
doctrine:
    orm:
        mappings:
            anh_taggable: { mapping: false }
```