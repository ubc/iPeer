# LTI-RI tests

## Checkout debug branch

```bash
cd ~/Code/ctlt/iPeer
git checkout lti-1.3-debug
docker-compose up -d
```

## Browse to the LTI 1.3 index

<http://localhost:8080/lti13>

## Perform manual test with LTI-RI

- [Resource Link](https://lti-ri.imsglobal.org/platforms/652/resource_links/10261/connects?user_id=93176)
- [Deep Link](https://lti-ri.imsglobal.org/platforms/652/contexts/4287/deep_links)

> Success with both URLs 2019-12-12

[app/views/lti13/launch.ctp](app/views/lti13/launch.ctp) is a debug view file that is displaying the `$_POST` and the JWT data.
