Deploying iPeer on Amamzon Web Services
=======================================

This instruction uses CloudFormation template to deploy iPeer application to AWS. By default, it will deploy a MySQL RDS as the backend database, 1 instance of EC2 and 2 docker containers for the frontend, a load balancer to accept the request.

The parameters in the template can be changed based on deployment requirement.

Prerequisites
-------------
* AWS account with billing enabled
* [AWS commandline tool](https://aws.amazon.com/cli/)
* The files in this directory


Deploying iPeer
---------------
Replace YOUR_KEY and YOUR_AWS_SUBNET with appropriate value in your AWS environment. You may also want to replace the default database password in the template.
```bash
export KEYNAME=YOUR_KEY
export SUBNET=YOUR_AWS_SUBNET
aws cloudformation create-stack --stack-name ipeer --template-body file:///`pwd`/ipeer.template.json --parameters ParameterKey=KeyName,ParameterValue=$KEYNAME ParameterKey=SubnetID,ParameterValue=$SUBNET --capabilities CAPABILITY_IAM
```
The provision progress can be monitor through AWS CloudFormation console. The application URL can be found in `output` tab in the console.

Tearing Down
--------------------

```bash
aws cloudformation delete-stack --stack-name ipeer
```
