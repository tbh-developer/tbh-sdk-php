# Translate By Humans API - PHP SDK

[![N](https://lh3.googleusercontent.com/-z8EJDa6UBiQ/WL_IhyM9lLI/AAAAAAAADss/bIbhhxZaJtQC5A_BjC83_9SJdjjfgxKagCL0B/h68/2017-03-08.png )](https://www.translatebyhumans.com/)

###### Translate By Humans API is a paid service. You must have a developer account to access the API and Sandbox environment.

## How to get Developer account ?

Learn more about getting a developer account [here](https://dev.translatebyhumans.com/introduction/sandbox).

## How to configure?

Once you’ve downloaded the SDK, locate **config.php** in **downloadedSDK/example/**. You will need to configure your Client ID and Client SECRET in the code so open the file **config.php** in the choice of your text editor.

Please note that the Client ID & Client SECRET are different for live environment and Sandbox environment.

1.  For sandbox environment, login to Developers Console ([https://dev.translatebyhumans.com](https://dev.translatebyhumans.com)) and navigate to the [credentials](http://localhost/tbhdeveloper/settings/credentials) page under settings. Copy the Client ID & Client SECRET from here.
2.  For live environment, login to Translate By Humans website ([https://www.translatebyhumans.com](https://www.translatebyhumans.com)) and navigate to the [credentials](https://www.translatebyhumans.com/en/settings/apisetting) page under settings. Copy the Client ID & Client SECRET from here.

Now that you have the Client ID & Client SECRET with you, let’s go back to the file *filename* that you found in the SDK. Look at the example below and search for this code in the file.

### Example:

     const CLIENT_KEY = 'TBH-XXXXXXXXXXX';
     const CLIENT_SECRET = '**************************************';
     const CONSUMER_PUBLIC = '**************************************';
     Const SANDBOX = true; 
     
You need to set Client id & secret on creating object of Tbh Client class.

You will have to set your Client ID & Client SECRET for the variables CLIENT_ID & CLIENT_SECRET. Remember that these values are different for live environment & sandbox environment so use values for the environment you want to enable.

The variable “SANDBOX” when set to true implies that you wish to enable the sandbox environment. Setting it to false will enable live environment.

$apiclient = new TbhClient(..,..,..,..,..) will create a new object of the Translate By Humans Client class.

###### Feel free to contact us regarding API