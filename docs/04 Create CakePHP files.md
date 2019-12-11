# Create CakePHP files

## Code reference

- <https://api.cakephp.org/1.3>
- <https://github.com/ubc/iPeer>

### Existing LTI files

- [lti_controller.php](app/controllers/lti_controller.php)
- [lti_requester.php](app/controllers/components/lti_requester.php)
- [lti_verifier.php](app/controllers/components/lti_verifier.php)

## Make new files

```bash
cd ~/Code/ctlt/iPeer
git checkout lti-1.3

touch app/controllers/lti13_controller.php
touch app/models/lti13.php
mkdir -p app/views/lti13
touch app/views/lti13/index.ctp
```

In `app/controllers/lti13_controller.php`:

```php
class Lti13Controller extends AppController
{
    public function index()
    {}

    public function login()
    {}

    public function launch()
    {}
}
```

In `app/models/lti13.php`:

```php
class Lti13 extends AppModel
{
    public $name = "Lti13";
    public $useTable = false;
}
```

In `app/views/lti13/index.ctp`:

```html
<p>LTI 1.3 tool index page.</p>
```
