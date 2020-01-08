# Create CakePHP files

## Code reference

- <https://api.cakephp.org/1.3>
- <https://github.com/ubc/iPeer>

### Existing LTI 1.1 files

- [app/controllers/lti_controller.php](app/controllers/lti_controller.php)
- [app/controllers/components/lti_requester.php](app/controllers/components/lti_requester.php)
- [app/controllers/components/lti_verifier.php](app/controllers/components/lti_verifier.php)

## Make new files

```bash
cd ~/Code/ctlt/iPeer
git checkout lti-1.3-debug

touch app/controllers/lti13_controller.php
touch app/models/lti13.php
mkdir -p app/views/lti13
touch app/views/lti13/launch.ctp
```

[app/controllers/lti13_controller.php](app/controllers/lti13_controller.php)

```php
class Lti13Controller extends AppController
{
    public $uses = array('Lti13');

    public function __construct()
    {
        parent::__construct();
    }
}
```

[app/models/lti13.php](app/models/lti13.php)

```php
class Lti13 extends AppModel
{
    public $useTable = false;

    public function __construct()
    {
    }
}
```

[app/views/lti13/launch.ctp](app/views/lti13/launch.ctp)

```html
<h3>LTI 1.3 tool temporary launch page.</h3>
```
