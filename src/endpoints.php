<?php
//Host
define("HOST_URL", "https://api.okra.ng/v1");

//identity
define("ALL_IDENTITY", "/products/identities");
define("IDENTITY_BY_ID", "/identity/getById");
define("IDENTITY_BY_CUSTOMER_ID", "/identity/getByCustomer");
define("IDENTITY_BY_DATE_RANGE", "/identity/getByDate");

//transactions
define("ALL_TRANSACTION", "/products/transactions");
define("TRANSACTION_BY_CUSTOMER_ID", "/transaction/getByCustomer");
define("TRANSACTION_BY_ACCOUNT_ID", "/transaction/getByAccount");
define("TRANSACTION_BY_DATE_RANGE", "/transaction/getByDate");
define("TRANSACTION_BY_BANK", "/transaction/getByBank");
define("SPENDING_PATTERN_BY_CUSTOMER_ID", "/products/transactions/spending-pattern");
define("TRANSACTION_PER_CUSTOMER_ID_BY_DATE_RANGE", "/transaction/getByDate");

//auth
define("ALL_AUTH", "/products/auths");
define("AUTH_BY_ID", "/auth/getById");
define("AUTH_BY_CUSTOMER_ID", "/auth/getByCustomer");
define("AUTH_BY_DATE_RANGE", "/auth/getByDate");
define("AUTH_BY_BANK", "/auth/getByBank");
define("AUTH_PER_CUSTOMER_ID_BY_DATE_RANGE", "/auth/getByCustomerDate");

//balance
define("ALL_BALANCE", "/products/balances");
define("BALANCE_BY_ID", "/balance/getById");
define("BALANCE_BY_CUSTOMER_ID", "/balance/getByCustomer");
define("BALANCE_BY_ACCOUNT_ID", "/balance/getByAccount");
define("BALANCE_BY_DATE_RANGE", "/balance/getByDate");
define("BALANCE_BY_TYPE", "/balance/getByType");