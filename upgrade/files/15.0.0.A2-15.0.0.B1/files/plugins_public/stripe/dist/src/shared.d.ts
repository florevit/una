import type { IStripeConnectInitParams, StripeConnectInstance, ConnectElementTagName } from "../types";
export type LoadConnectAndInitialize = (initParams: IStripeConnectInitParams) => StripeConnectInstance;
export type ConnectElementHTMLName = "stripe-connect-payments" | "stripe-connect-payouts" | "stripe-connect-payment-details" | "stripe-connect-payment-disputes" | "stripe-connect-disputes-list" | "stripe-connect-account-onboarding" | "stripe-connect-payment-method-settings" | "stripe-connect-account-management" | "stripe-connect-notification-banner" | "stripe-connect-instant-payouts" | "stripe-connect-instant-payouts-promotion" | "stripe-connect-issuing-card" | "stripe-connect-issuing-cards-list" | "stripe-connect-financial-account" | "stripe-connect-financial-account-transactions" | "stripe-connect-recipients" | "stripe-connect-capital-financing" | "stripe-connect-capital-financing-application" | "stripe-connect-capital-financing-promotion" | "stripe-connect-capital-overview" | "stripe-connect-documents" | "stripe-connect-product-tax-code-selector" | "stripe-connect-export-tax-transactions" | "stripe-connect-tax-registrations" | "stripe-connect-tax-settings" | "stripe-connect-tax-threshold-monitoring" | "stripe-connect-balances" | "stripe-connect-payouts-list" | "stripe-connect-payout-details" | "stripe-connect-app-install" | "stripe-connect-app-viewport" | "stripe-connect-reporting-chart" | "stripe-connect-check-scanning";
export declare const componentNameMapping: Record<ConnectElementTagName, ConnectElementHTMLName>;
type StripeConnectInstanceExtended = StripeConnectInstance & {
    debugInstance: () => Promise<StripeConnectInstance>;
};
interface StripeConnectWrapper {
    initialize: (params: IStripeConnectInitParams) => StripeConnectInstance;
}
export declare const findScript: () => HTMLScriptElement | null;
export declare const isWindowStripeConnectDefined: (stripeConnect: unknown) => boolean;
export declare const loadScript: () => Promise<StripeConnectWrapper>;
export declare const initStripeConnect: (stripePromise: Promise<StripeConnectWrapper>, initParams: IStripeConnectInitParams) => StripeConnectInstanceExtended;
export {};
