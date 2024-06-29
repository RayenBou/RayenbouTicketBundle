# TicketBundle

This project provides a PHP integration for a ticketing system, allowing for the fetching of individual tickets and lists of tickets from a specified API. It utilizes the Symfony HttpClient component to communicate with the API and handles authentication through bearer tokens.

## Features

- Fetch individual tickets by ID.
- Fetch a list of all tickets.
- Authentication via JWT tokens.
- Error handling for HTTP and transport exceptions.

## Requirements

- PHP 8.1 or higher.
- Symfony HttpClient component.
- Valid JWT token returned by been authenticate with the server side ticket bundle for authentication.

## Installation

To use this integration in your project, follow these steps:

1. Ensure you have Composer installed.
2. Add this project as a dependency using Composer:

``composer require rayenbou/ticket-bundle``

## Ticket service 

The ticket Service provide simple method to create, read and update a ticket.

# create new ticket
```
$ticketService->createTicket([
                'title' => 'This is a ticket',
                'description' => 'This is the description of the ticket',
            ]);
```

# add a message to an already existing ticket
```
$ticketService->modifyTicket([
                'id'    => 1,
                'description' =>'This is the description of the ticket',
            ]);
```
# get all tickets from a given email 
```
'tickets' => $ticketService->findAll()
```
# get one ticket from a given email
```
$ticketService->find($id)
```

## Error Handling
Errors during API calls are returned as Exception objects. 

Custom Exception existing:

`AuthenticationFailedException` : Something about wrong credentials , check that yours Env var a corectly filled with username,password and domain from the server side of the ticket bundle.
`TicketException`: Failed to create or get tickets, this is mainly caused by server side of ticket bundle.
`TicketFetchException`: More precise exception that give the id of the ticket


## Contributing
Contributions to this project are welcome. Please ensure to follow the existing coding style and add unit tests for any new or changed functionality.

## License
This project is licensed under the MIT License - see the LICENSE file for details.



```