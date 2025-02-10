# Adjustments 
### 1. Payloads for Customer, Product or Order
To modify data sent for occurences related to one of resources, you can adjust classes responsible for payload building.
These classes are located under `src/Builder/Payload` directory and have meaningful names corresponding to data that will be provided, e.g. `CustomerPayloadBuilder`or `OrderPayloadBuilder`.

### 2. Requests from User.com on designated endpoint.
Requests send on endpoint designed by User.com are handled by `\BitBag\SyliusUserComPlugin\Controller\UserComAgreementsController`.
You can adjust this controller to handle requests in a way that suits your needs.
There is designated validator to check if request payload contains required fields.
If there will be any field added by User.com, you can adjust this validator to check if this field is present in request payload.
Also, in `BitBag\SyliusUserComPlugin\Assigner\AgreementsAssigner` you can adjust existing logic to assign agreements to customer in a way that suits your needs.

```php
public function assign(CustomerInterface $customer, array $agreements): void
    {
        foreach ($agreements as $key => $value) {
            match ($key) {
                'email_agreement' => $customer->setSubscribedToNewsletter($value),
                default => $this->logger->error(
                    sprintf(
                        'Agreement not found. Key = %s, Value = %s, CustomerId = %s',
                        $key,
                        $value,
                        $customer->getId(),
                    ),
                ),
            };
        }
    }
}
```
