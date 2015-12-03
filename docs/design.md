Design
======

Event Store is not Event Sourcing, it is a database that can be used for event sourcing.

## Authentication

Authentication should be optional; by default the event store has a default Consumer called 'admin' that is passwordless 
and has all permissions globally. This means that the default Consumer is allowed to read and write events but also to
add consumers and set their permissions

- Adding a Role to a Consumer will automatically create that Role?

## Domain Model:

### Events

- Write event(s) to Stream
- Read events from Stream
- Remove Stream
- Make snapshot
- Add Consumer
- Disable Consumer
- Grant permission on Stream to Consumer
- Revoke permission on Stream from Consumer
- Grant global permission to Consumer
- Revoke global permission from Consumer
- Add Role to Consumer
- Revoke Role from Consumer
- Grant permission on Stream to Role
- Revoke permission on Stream from Role
- Grant global permission to Role
- Revoke global permission from Role

### Entities

- Consumer
- Role
- Permission
- Stream
- Event
- StreamSnapshot

Application Layer:

- DoctrineDbalAdapter
