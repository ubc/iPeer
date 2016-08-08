Deploying iPeer on Google Container Engine/Kubernetes
==================================================

This instruction is to deploy the iPeer application on Google cloud platform using container engine. Since GCE is using Kubernetes to manage containers, iPeer could also be deployed to any Kubernetes cluster using this instruction.


Prerequisites
-------------
* Google Cloud Platform account with billing enabled
* Google Cloud SDK commandline tool - [gcloud](https://cloud.google.com/sdk/). If you can't/don't want to install it, you can use commandline tool provided in Google Cloud Console.
* Google project created. We use `ipeer-docker`.
* The files in this directory

Setting Up Environment
----------------------
This step is required when you use gcloud on your local environment. If you use commandline tool in Google Cloud Console or your own Kubernetes instance, you can skip to the next step.

# Logging in
```bash
gcloud auth login
```

# Setup Zone
Replace `us-west1-a` with any zone of your preference.

```bash
gcloud config set compute/zone us-west1-a
```

# Set Default Project
```bash
gcloud config set project ipeer-docker
```

# Creating Kubernetes Cluster
The cluster can be customized by adding additional parameters to `clusters create` command, e.g. --machine-type or --num-nodes
```bash
gcloud container clusters create cluster-ipeer
gcloud container clusters get-credentials cluster-ipeer --project ipeer-docker
```

# Installing kubectl
```bash
gcloud components install kubectl
```

Deploying iPeer
---------------

# Creating Persistent Disk for Database (GCE only)
```bash
gcloud compute disks create --size=10GB ipeer-1
```
If you are using your own Kubernetes instance, you may want to provision a persistent disk for your database instead of using host path.

# Creating GCE Volume (GCE only)
```bash
kubectl create -f gce-volumes.yaml
```

# Setting Up Database Password
Change the password in password.txt. Make sure there is no new line at the end of the file.
```bash
kubectl create secret generic mysql-pass --from-file=password.txt
```

# Creating MySQL Deployment
```bash
kubectl create -f mysql-deployment.yaml
```

# Creating iPeer Deployment
```bash
kubectl create -f ipeer-deployment.yaml
```

# Getting the Public IP for iPeer Service
```bash
kubectl describe services ipeer
```
The public IP is listed under External IP field. You may need to wait a little bit for the load balancer getting provisioned.

Operating iPeer Cluster
-----------------------

# Scaling Up/Down
When the load increases, you can scale up your iPeer app instances. Change `3` to the number of iPeer instances you would like to scale up to.
```bash
kubectl scale deployment/ipeer --replicas=3
```

Removing iPeer From Cluster
---------------------------

```bash
kubectl delete deployment,service -l app=ipeer
kubectl delete secret mysql-pass
kubectl delete pvc -l app=ipeer
kubectl delete pv ipeer-pv-1
```

Tearing Down Cluster
--------------------

```bash
gcloud container clusters delete cluster-ipeer
gcloud compute disks delete ipeer-1
```
