# okra-php-wrapper
A PHP Wrapper for [Okra](https://okra.ng/)  

<img src="https://pbs.twimg.com/profile_images/1199677745262989314/_D2jAMbu_400x400.jpg" alt="drawing" width="200"/>  

## Requirements
- guzzlehttp/guzzle: ~6.0
- PHP 7.0 or more recent  

## Install

### Via Composer
``` bash
$ composer require mmuodev/okra-php-wrapper
```  
## Usage  

The wrapper provides a simple interface to use Okra's core products - Auth, Identity, Balance and Transaction. Each of these products are represented by its own class.
You need to initiate a class (and pass your Okra's bearer token), to access its methods. All methods return an array, thus making it easy to use in your application.


### 1. Auth  
Okra offers a path for a customer to successfully verify their bank. The customer enters their credentials and are authenticated immediately.  

```php
$auth = new Auth(BEARER_TOKEN);
```

#### a. Get all Auth 
This returns auth information in an array or pdf format for all customers.  
```php
$auth->getAllAuth();
```
> Optional: Pass the boolean value `true` to return the data in pdf format  

#### b. Get Auth for a customer
This returns auth information for a cutomer using auth id
```php
$auth->getAuthById();
```
> Required: Pass the `auth id`  

#### c. Get Auth by customer id
This returns auth information for a cutomer using customer id
```php
$auth->getAuthByCustomerId();
```
> Required: Pass the `customer id`  

#### d. Get Auth by date range
This returns auth information based on date range they were created.
```php
$auth->getAuthByDateRange(from, to);
```
> Method expects 5 parameters - first 2 are required while last 3 optional. 
> The first 2 parameters - `from` and `to` are the date ranges in this format `2020-05-10`.  
> Pass the boolean value `true` for the third parameter to return all records. Ignore to return one page.  
> The penultimate and last parameters - `page` and `limit` are integers  stating the `page` number you wish
> to return and the number of records - limit - to return on this page. Ignore these if you are fetching all records.

> For example, to fetch all auth records between `2020-05-01` and `2020-05-20`, you do this:
```php
$auth->getAuthByDateRange('2020-05-01', '2020-05-20', true)
```  
> To fetch only page 2 and limit the number of records to 10 for the same date range as above, you do this:
```php
$auth->getAuthByDateRange('2020-05-01', '2020-05-20', ,2,10)
```  

### 2. Transaction  
Receive customer-authorized transaction data for current, savings, and domiciliary Accounts.  

```php
$transaction = new Transaction(BEARER_TOKEN);
```  
#### a. Get all Transactions 
This returns summarized info on the transactions  in an array or pdf format.  
```php
$transaction->getTransactions();
```
> Optional: Pass the boolean value `true` to return the data in pdf format  

#### b. Get transactions for a customer
This returns transactions for a particular customer
```php
$transaction->getTransactionsPerCustomer(customer_id);
```
> Method expects 4 parameters - first is required while last 3 optional. 
> The first - `customer_id` is the `id` of the customer returned from the previous method.   
> Pass the boolean value `true` for the second parameter to return all records. Ignore to return one page.  
> The penultimate and last parameters - `page` and `limit` are integers  stating the `page` number you wish
> to return and the number of records - limit - to return on this page.  Ignore these if you are fetching all records.

#### c. Get Transactions by date range
This returns transactions filtered by date range
```php
$transaction->getTransactionsByDateRange();
```
> Method expects 5 parameters - first 2 are required while last 3 optional. 
> The first 2 parameters - `from` and `to` are the date ranges in this format `2020-05-10`.  
> Pass the boolean value `true` for the third parameter to return all records. Ignore to return one page.  
> The penultimate and last parameters - `page` and `limit` are integers  stating the `page` number you wish
> to return and the number of records - limit - to return on this page. Ignore these if you are fetching all records.

> For example, to fetch all transaction records between `2020-05-01` and `2020-05-20`, you do this:
```php
$auth->getTransactionsByDateRange('2020-05-01', '2020-05-20', true)
```  
> To fetch only page 2 and limit the number of records to 10 for the same date range as above, you do this:
```php
$auth->getTransactionsByDateRange('2020-05-01', '2020-05-20', ,2,10)  
```
#### d. Get Transactions per bank  

This returns transactions filtered by bank
```php
$transaction->getTransactionsPerBank(bank_id);
```
> Method expects 4 parameters - first is required while last 3 optional. 
> The first - `bank_id` is the id of the bank. Each bank has a unique id.  
> Pass the boolean value `true` for the second parameter to return all records. Ignore to return one page.  
> The penultimate and last parameters - `page` and `limit` are integers  stating the `page` number you wish
> to return and the number of records - limit - to return on this page. Ignore these if you are fetching all records.  

#### e. Get Spending Pattern
This returns spending pattern for a cutomer using customer id
```php
$transaction->getSpendingPatternPerCustomer(customer_id);
```
> Required: Pass the `customer id`  

### 3. Identity  
Retrieve various account holder information on file with the bank, including names, emails, phone numbers, and addresses  

```php
$identity = new Identity(BEARER_TOKEN);
```

#### a. Get all Identities 
This returns identities' information in an array or pdf format for all customers. 
```php
$identity->getIdentities();
```
> Optional: Pass the boolean value `true` to return the data in pdf format  

#### b. Get Identity for a customer using `id` 
This returns identity information using `id` returned from the above method.
```php
$identity->getIdentityById(id);
```
> Required: Pass the `identity id`  

#### c. Get Identity for a customer using `customer id` 
This returns identity information for a customer using customer id.
```php
$identity->getIdentityByCustomerId(customer_id);
```
> Required: Pass the `customer_id`  

#### d. Get Identity by date range
This returns identity information based on date range they were created.
```php
$identity->getIdentityByDateRange(from, to);
```
> Method expects 5 parameters - first 2 are required while last 3 optional. 
> The first 2 parameters - `from` and `to` are the date ranges in this format `2020-05-10`.  
> Pass the boolean value `true` for the third parameter to return all records. Ignore to return one page.  
> The penultimate and last parameters - `page` and `limit` are integers  stating the `page` number you wish
> to return and the number of records - limit - to return on this page. Ignore these if you are fetching all records.

> For example, to fetch all records between `2020-05-01` and `2020-05-20`, you do this:
```php
$identity->getIdentityByDateRange('2020-05-01', '2020-05-20', true)
```  
> To fetch only page 2 and limit the number of records to 10 for the same date range as above, you do this:
```php
$identity->getIdentityByDateRange('2020-05-01', '2020-05-20', ,2,10)
```  

### 4. Balance  
Retrieve retrieve the real-time balance for each of a record's accounts.  

```php
$balance = new Balance(BEARER_TOKEN);
```

#### a. Get all Balances 
This returns balance information in an array or pdf format for all customers. 
```php
$balance->getAllBalance();
```
> Optional: Pass the boolean value `true` to return the data in pdf format  

#### b. Get balance for a customer using `id` 
This returns balance information using `id` returned from the above method.
```php
$balance->getBalanceById(id);
```
> Required: Pass the `balance id`  

#### c. Get balance for a customer using `customer id` 
This returns balance information for a customer using customer id.
```php
$balance->getBalanceByCustomerId(customer_id);
```
> Required: Pass the `customer_id`  

#### d. Get balance by date range
This returns balance information based on date range they were created.
```php
$balance->getBalanceByDateRange(from, to);
```
> Method expects 5 parameters - first 2 are required while last 3 optional. 
> The first 2 parameters - `from` and `to` are the date ranges in this format `2020-05-10`.  
> Pass the boolean value `true` for the third parameter to return all records. Ignore to return one page.  
> The penultimate and last parameters - `page` and `limit` are integers  stating the `page` number you wish
> to return and the number of records - limit - to return on this page. Ignore these if you are fetching all records.

> For example, to fetch all records between `2020-05-01` and `2020-05-20`, you do this:
```php
$balance->getBalanceByDateRange('2020-05-01', '2020-05-20', true)
```  
> To fetch only page 2 and limit the number of records to 10 for the same date range as above, you do this:
```php
$balance->getBalanceByDateRange('2020-05-01', '2020-05-20', ,2,10)
```  
## Credit  
[Obioha Uche](https://mmuo.dev)  






