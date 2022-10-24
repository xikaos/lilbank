# Lilbank
## Objective
The objective of this project was to practice the implementation of the repository pattern and practice writing unit and integration tests. I'm not following Domain Driven Design but the project structure can remind you of it.

## Project Setup
Sorry about the extra commands regarding permissions. Follow the steps above:

1. Clone this repository.
2. Run `docker-compose -f development.yml up -d`.
3. Run `docker-compose -f development.yml exec app sudo composer install` to install the dependencies.
4. Run `docker-compose -f development.yml exec app sudo chmod -R o+rx vendor` to allow the application to read the dependencies.
5. Run `docker-compose -f development.yml exec app sudo touch /var/www/app/.phpunit.result.cache` to create the `phpunit` cache file.
6. Run `docker-compose -f development.yml exec app sudo chmod o+rw /var/www/app/.phpunit.result.cache` to allow `phpunit` to write to the cache file.
7. Run `php artisan test` to run the application tests.


## Foreword
When I started this project, my interest was having Ubiquous Domain Language applied to everything so I could see how it looks. That's why almost everything in this project is in portuguese. Because it's my native language.
## Context
Lilbank is a small banking company that do business in a simulated universe where storage is REALLY expensive. Based on that, the CTO asked you to develop a banking application that is able to do some simple operations:

* Transfer money from one account to another.

That's it.

Since I was interested in practicing the repository pattern, I tried to came up with an application that would have very simple domain operations and the core complexity would be related to how the domain business rules interact with application/infrastucture logic.

## The Project Structure
The project is sectioned between **Application Layer** and **Domain Layer**. When I started I didn't knew nothing about the **Infrastructure Layer**.

### Application
This layer is responsible for providing the **Domain Layer** the means to achieve its goals outside the domain. In this app, if something needs to be readed/persisted, the application layer will handle that.

The main component of this layer is a class that implements the `ContaRepositoryContract` interface. Any class that implements this interface is able to provide the methods to read/write bank accounts in some medium.

### Domain
Here is were we have core domain logic and operations. Some things that are worth mentioning are described above.

#### Contracts
Living inside `app/Contracts/Domain/` we have our domain interfaces. Those are used to establish:

1. What a value-object is inside the domain (`ValorObjeto.php`).
2. What an account repository is inside the **Domain Layer** (`ContaRepositoryContract.php`).
3. What a class that performs account transfers is inside the **Domain Layer** (`TransferenciaServiceContract.php`).

You might be asking: why do we need a domain interface and an application interface for the account repository? That's because the account transfer service should not be talking to **Application Layer** repositories. All them are abstracted inside a single **Domain Layer** repository.

#### Policies
The concrete `CategoriaContaPolicy.php` class is used to check which category a specific account pertains. It made sense to me to abstract this in a class separated from the repositories because they are not interested about account details about the account they are reading/writing.

## Future
I thought about creating different repositories and account categories, some options came to mind:

* S3 Buckets
* FTP
* NTFS
* IPFS

Besides that, I tought about having some sort of class that would do the mapping of account category -> repository in order to make the account repository (domain) simpler.

Honestly, I believe this project fulfilled it's purpose. I'm not planning to continue working on it.

Thanks for checking it out xD.
