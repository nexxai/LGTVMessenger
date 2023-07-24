import asyncio
import argparse

from aiowebostv import WebOsClient


async def main():
    help_message = 'Connect to an LG webOS-based TV and display a message at the bottom of the screen'
    parser = argparse.ArgumentParser(description=help_message)

    parser.add_argument("-t", "--target", help="IP of TV", required=True)
    parser.add_argument("-m", "--message",
                        help="Message to send", required=True)
    parser.add_argument(
        "-k", "--key", help="The client key to connect to the TV", required=True)

    args = parser.parse_args()

    client = WebOsClient(args.target, args.key)
    await client.connect()

    message = await client.send_message(args.message)

    await client.disconnect()


if __name__ == "__main__":
    asyncio.run(main())
