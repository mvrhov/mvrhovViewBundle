### View bundle for Symfony

This bundle adds two View classes that you can return from your controller's action `RouteView`, `TemplateView` and 
 an interface `ResponderInterface` which the class you are returning from the controller should implement. 


### Installation

1. Require View bundle as a dependency using Composer:

    ```
    php composer.phar require mvrhov/view-bundle
    ```

2. Add bundle to `app/AppKernel.php`

    ```php
       public function registerBundles()
       {
           return array(
               new \mvrhov\ViewBundle\mvrhovViewBundle();
               // ...
           );
       }
    ```

3. You are done.



### Examples

## RouteView

```php
use mvrhov\ViewBundle\View\RouteView;
use mvrhov\ViewBundle\View\ViewInterface;

final class RouteAction
{
    public function __invoke(): ViewInterface
    {
        $params = [
            'param1' => 'view',
            'param2' => 'bundle',
        ];

        return new RouteView('my_route', $params);
    }
}
```

## TemplateView

```php
use mvrhov\ViewBundle\View\TemplateView;
use mvrhov\ViewBundle\View\ViewInterface;

final class TemplateAction
{
    public function __invoke(): ViewInterface
    {
        $data = [
            'foo' => 1,
            'bar' => 'yep'
        ];

        return new TemplateView('@Bundle/template.html.twig', $data);
    }
}
```

## Responder

```php
use mvrhov\ViewBundle\View\ResponderInterface;
use mvrhov\ViewBundle\View\TemplateView;
use mvrhov\ViewBundle\View\RouteView;
use mvrhov\ViewBundle\View\ResponseView;

final class InvoiceResponder implements ResponderInterface;
{
    private $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function getView(Request $request, int $requestType): ViewInterface
    {
        if ('application/json' !== $request->getContentType()) {
            $total = count($this->invoices);
    
            if (0 === $total) {
                return new RouteView('list_invoices');
            }
    
            if (1 === $total) {
                return new TemplateView('@Bundle/template_one.html.twig', $this->invoices);
            }
            
            if (5 > $total) {
                return new TemplateView('@Bundle/template_a_lot.html.twig', $this->invoices);
            }
        } else {
            return new ResponseView(new Response(json_serialize($this->invoices)));
        }
    }
}


use mvrhov\ViewBundle\View\TemplateView;
use mvrhov\ViewBundle\View\ResponderInterface;

final class ResponderAction
{
    public function __invoke(): ResponderInterface
    {
        $invoices = $this->getInvoices();

        return new InvoiceResponder($invoices);
    }
}
```