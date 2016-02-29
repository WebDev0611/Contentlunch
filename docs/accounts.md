# Subscriptions/Accounts/Agency info


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
  - account_id - which account
  - subscription_level - which Subscription plan
  - licenses - ???
  - monthly_price - How much we charge per month for this plan (/12 for annual)
  - annual_discount - unused
  - training - ???
  - features - ??? always "API, Premium Support, Custom Reporting, Advanced Security" - to be removed
  - created_at/updated_at - self-explanatory
  - subscription_type - one of:
      - trial - on free trial
      - client - a child account of an agency plan
      - freemium - our free-forever plan
      - paid - a paid plan
      - complementary - a paid plan, but given out for free by us

# Stripe

Stripe has 6 plans set up with id's:
  pro / $99
  enterprise / $249
  agency / $99
  pro-annual / $1068
  enterprise-annual / $2700
  agency-annual / $1068

For pro and enterprise, that's the per-user price.
For agency, that's the per-client price.
-annual plans are per 12 months, otherwise every month

The id in stripe has to match a stripe_id in a Subscription model

Stripe makes webhook calls to /stripe_webhook

# Emails

Stripe sends a receipt email for successful charges
stunning.co for others



# Login Sequence

Upon login, the user is redirected to / and the AppController kicks in.  It's purpose is to determine this user's
primary account and then redirect them to that account specific URL.  It will give preference to an agency acocunt over
a client acocunt.

