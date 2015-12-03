EventStore ACL
==============

By using Access Control Lists it is possible to provide multi-tenancy or basic authorization support
for the Event Store.

With an Access Control List you can configure which Consumer has priviliges on a specific stream.

TODO!!: Consider the possibility to split an event stream into groupings and that you can add priviliges to a group.
This will allow us to have a single stream for all aggregates and still be able to assign rights to a whole stream,
a group and thus individual aggregate or a group of aggregates.
