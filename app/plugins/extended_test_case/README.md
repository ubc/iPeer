# Extended Test Case

Extended test case aims to make the displeasure of using
`CakeTestCase::testAction()` slightly more pleasurable.

## Usage

Above your test case, use:

    App::import('Lib', 'ExtendedTestCase.ExtendedTestCase');

Then extend your test case with `ExtendedTestCase` instead of `CakeTestCase` and
define the `testController` variable in your test case as the controller you are
testing.

For more information about utilizing mocks, etc. visit [here][1].

Another handy feature that `ExtendedTestCase` adds is the ability to just test
certain methods. This is especially useful when you are making minor changes to
one method (say, a controller method) who's test lies in a large test case that
loads a lot of fixtures. While testing internally, you can conveniently add an 
array of methods that you _want_ to run in a `$testMethods` var on your test case.

    class MyTestCase extends ExtendedTestCase {
        var $testMethods = array('testThis');

        function testThis() {} // will run
        function testThat() {} // will not run
    }

Since no other tests run, you'll only need to load the database and fixtures for
the method you care about at the time. 

__Remember to remove `$testMethods` before you commit anything :)__

## Features

- Falls back to old `testAction` method if `ExtendedTestCase::testController`
is not defined
- Allows use of mocks for things like the Email component or your controller
actions (render, header, etc.) in tests
- Simulates most of what the controller action does, minus actually rendering
anything
- Allows testing a subset of test methods within a large test case

## License

Licensed under The MIT License
[http://www.opensource.org/licenses/mit-license.php][4]
Redistributions of files must retain the above copyright notice.

## Links

- [http://mark-story.com/posts/view/testing-cakephp-controllers-the-hard-way][2]
- [http://mark-story.com/posts/view/testing-cakephp-controllers-mock-objects-edition][3]
- [http://www.42pixels.com/blog/testing-controllers-the-slightly-less-hard-way][1]

[1]: http://www.42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
[2]: http://mark-story.com/posts/view/testing-cakephp-controllers-the-hard-way
[3]: http://mark-story.com/posts/view/testing-cakephp-controllers-mock-objects-edition
[4]: http://www.opensource.org/licenses/mit-license.php
