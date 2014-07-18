<?php

// Seed for the master list of content types
// When updating this list, use a migration to sync
// the database with this list
// Name, key, base_type, visible (in UI)
return [
  ['Audio Recording', 'audio-recording', 'audio', true],
  ['Blog Post', 'blog-post', 'blog_post', true],
  ['Case Study', 'casestudy', 'document', true],
  ['Direct Upload', 'direct-upload', 'attached_file', false],
  ['Ebook', 'ebook', 'attached_file', true],
  ['Email', 'email', 'email', true],
  ['Feature Length Article', 'feature-article', 'document', true],
  ['Newsletter', 'newsletter', 'document', true],
  ['Landing Page', 'landing-page', 'long_html', true],
  ['Photo', 'photo', 'photo', true],
  ['Sales Letter', 'sales-letter', 'document', true],
  ['Sell Sheet Content', 'sellsheet-content', 'document', true],
  ['Social Media Post', 'social-media-post', 'social_media_post', true],
  ['Video', 'video', 'video', true],
  ['Website Page', 'website-page', 'long_html', true],
  ['Whitepaper', 'whitepaper', 'document', true],
  ['Workflow Email', 'workflow-email', 'email', true],
];