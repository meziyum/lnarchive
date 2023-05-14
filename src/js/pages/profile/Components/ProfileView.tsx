
import React from "react";

interface props {
    name: string;
    coverURL: string;
    bannerURL: string;
    gender: string;
}

export default function ProfileView({name, coverURL, bannerURL, gender }: props): JSX.Element {
    return(
        <>
            <div id="banner">
                {bannerURL && <img alt='Profile Banner' src={coverURL}></img>}
            </div>
        </>
    );
}