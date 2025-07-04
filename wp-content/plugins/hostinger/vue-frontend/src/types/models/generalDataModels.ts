export type ToggleableSettingsData = {
  disableXmlRpc: boolean;
  forceHttps: boolean;
  maintenanceMode: boolean;
  forceWww: boolean;
  isEligibleWwwRedirect: boolean;
  disableAuthenticationPassword: boolean;
  enableLlmsTxt: boolean;
};

export type NonToggleableSettingsData = {
  bypassCode: string;
  currentWpVersion: string;
  newestWpVersion: string;
  phpVersion: string;
};

export type HostingerToolsData = {
  homeUrl: string;
  siteUrl: string;
  editSiteUrl: string;
  pluginUrl: string;
  assetUrl: string;
  translations: { [key: string]: string };
  restBaseUrl: string;
  nonce: string;
  wpVersion: string;
  phpVersion: string;
  llmstxtFileUrl: string
  llmstxtFileUserGenerated: boolean
};

export type SettingsData = NonToggleableSettingsData & ToggleableSettingsData;
