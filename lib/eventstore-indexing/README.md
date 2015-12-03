Indexing
========

Indexing is the act of building an event stream from another event stream where all events are actually links to another
event. By using indexing it is possible to optimize how a projection state is built.

Take the following example:

    Suppose you have a projection that only listens to 3 out of 1000 event types; it would be wasteful if
    you have to go through all 1000 event types just to project your data. An optimization that you could
    do is to create an index that will create a stream per event type and then only read the joined events 
    of the three types that you want to aggregate.
