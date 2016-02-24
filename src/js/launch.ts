// External typings first:
/// <reference path='typings/tsd.d.ts' />
/// <reference path='launch.d.ts' />


// Services
/// <reference path='services/auth-service.ts' />


// Controllers:
/// <reference path='controllers/agency-controller.ts' />
/// <reference path='controllers/home-controller.ts' />

// extend lodash
declare module _ {
    interface LoDashStatic {
        appendOrUpdate(a:any, b:any);
        findById(items, id);
        remove(array, item);
        stripTags(str);
        indexById(array, id);
        pluck(array, prop);
    }
}