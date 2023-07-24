import asyncio
import argparse

from aiowebostv import WebOsClient


async def main():
    help_message = 'Get the client key from an LG webOS TV'
    parser = argparse.ArgumentParser(description=help_message)

    parser.add_argument("-t", "--target", help="IP of TV", required=True)

    args = parser.parse_args()

    client = WebOsClient(args.target, None)
    await client.connect()

    print(f"{client.client_key}")

    await client.disconnect()


if __name__ == "__main__":
    asyncio.run(main())
