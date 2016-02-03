# Subscriptions/Accounts info


# Models

User's accounts go into User model

Account - Each company that signs up gets an entry in Account
  account_type:
    - single - pro or enterprise subscription
    - agency - parent account for an agency
    - client - child account for a client in an agency

  For Agency accounts, we set the parent_id of the clients to the parent Agency

Subscription represents a subscription plan

AccountSubscription ties a subscription to an Account
  todo - do we need this?  It should be a many to one relationship



# Stripe

Stripe has three plans set up with id's:
  pro / $99
  enterprise / $249
  agency / $99

For pro and enterprise, that's the per-user price.
For agency, that's the per-client price.

The id in stripe has to match a stripe_id in a Subscription model


